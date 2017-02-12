<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Source\Source;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Proxies\__CG__\AppBundle\Entity\Booking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/list", name="list")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function listingOldAction(Request $request){

        $source = new Entity('AppBundle:Product');

        /* @var $grid \APY\DataGridBundle\Grid\Grid */
        $grid = $this->get('grid');
        $grid->setSource($source);

        $MyTypedColumn = new DateColumn(array('id' => 'buy_count', 'title' => 'Need Count', 'source' => false, 'filterable' => false, 'sortable' => false));
        $grid->addColumn($MyTypedColumn);

        // Create an Actions Column
        $actionsColumn = new ActionsColumn('action_column', 'Action Column');
        $grid->addColumn($actionsColumn, 16);

        // Attach a rowAction to the Actions Column
        $rowAction1 = new RowAction('Show', 'show_single_id', false, '_self', array('class'=>'show_custom'));
        $rowAction1->setColumn('action_column');
        $grid->addRowAction($rowAction1);
        return $grid->getGridResponse('AppBundle:Default:my_grid.html.twig');
    }

    /**
     * @Route("/show/byid/{id}", name="show_single_id")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function showSingleAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();

        $single = $em->getRepository('AppBundle:Product')->findOneBy(array('id'=>$id));

        return $this->render('@App/Default/show.html.twig', array('product'=>$single));
    }

    /**
     * @Route("/medicine/{slug}", name="show_single")
     * @Template()
     */
    public function showAction(Request $request, $slug){

        $em = $this->getDoctrine()->getManager();

        $single = $em->getRepository('AppBundle:Product')->findOneBySlug($slug);

        return array('product'=>$single);
    }

    /**
     * @return GridBuilder
     */
    public function createGridBuilder(Source $source = null, array $options = [])
    {
        return $this->container->get('apy_grid.factory')->createBuilder('grid', $source, $options);
    }

    /**
     * @Route("/my_bag", name="my_bag")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function myBagAction(Request $request){

        return array('mybag');
    }

    /**
     * @Route("/orders", name="submit_order")
     * @QueryParam(name="state", nullable=true, requirements="\d+")
     * @Template()
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     */
    public function submitAction(Request $request, ParamFetcher $paramFetcher){

        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();

        $bookings = $em->getRepository('AppBundle:Booking')->findAllNewByClient($userId);

        if(count($bookings)>0 && !is_null($this->getUser()->getUserSettings()) && !empty($paramFetcher->get('state')) && (int)$paramFetcher->get('state') == 1){

            $invoice = new Invoice();
            $invoice->setStatus(Invoice::IS_NEW);

            $now = new \DateTime('now');
            $total = 0;
            $errorData = array();
            foreach($bookings as $booking){

                $product=$booking->getProduct();
                $product->setCount($product->getCount() - $booking->getCount());
                $booking->setStatus(Booking::IS_ORDERED);

                $booking->setInvoice($invoice);

                $em->persist($product);
                $em->persist($booking);
                $total +=$booking->getCost();
            }

            $invoiceSettings = $em->getRepository('AppBundle:InvoiceSettings')->findMax();

            if(!$invoiceSettings){
                $this->addFlash(
                    'notice',
                    'Project is not ready. Please add invoice settings.!'
                );
                return $this->redirectToRoute('my_bag');
            }

            $dueDate = clone $now;
            $shippingHandling = clone $now;
            $invoice->setTotal($total);
            $invoice->setDueDate($dueDate->modify("+{$invoiceSettings->getTerms()} day"));
            $invoice->setNumber($now->getTimestamp().$invoice->getId());
            $invoice->setTerms('Net ' . $invoiceSettings->getTerms());
            $invoice->setShippingHandling($shippingHandling->modify('+1 day'));
            $invoice->setUser($this->getUser());
            $em->persist($invoice);

            $validator = $this->get('validator');

            $errors = $validator->validate($invoice);

            if(count($errors) > 0 || count($errorData) > 0) {

                // returned value
                $returnResult = array();

                // check count
                if(count($errors) > 0){

                    // loop for error
                    foreach($errors as $error){
                        $returnResult[$error->getPropertyPath()] = $error->getMessage();
                    }
                }

                $this->addFlash(
                    'error',
                    'Your changes were saved!'
                );

                return $this->redirectToRoute('my_bag', array('message'=>$errorData));

            }
            $em->flush();

            $this->addFlash(
                'notice',
                'Your order were saved!'
            );
        }

        $invoices = $em->getRepository('AppBundle:Invoice')->findByAuthor($this->getUser()->getId());

        // get knp pagination
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($invoices, $this->get('request')->query->get('page', 1), 6);

        return array('pagination' => $pagination);
    }

    /**
     * @Route("/invoice/{invoiceId}", name="generate_invoice")
     * @Security("has_role('ROLE_USER')")
     */
    public function invoiceAction(Request $request, $invoiceId){

        $em = $this->getDoctrine()->getManager();
        $secure = $this->container->get('security.authorization_checker');

        if(!$secure->isGranted("ROLE_SUPER_ADMIN")){

            $invoices = $em->getRepository('AppBundle:Invoice')->findUniqByAuthorAndId($this->getUser()->getId(), $invoiceId);

            if(!$invoices){
                $this->addFlash(
                    'notice',
                    'Not have a permission!'
                );
                return $this->redirectToRoute('submit_order');
            }

            if(!$invoices->getTrackNumber()){
                $this->addFlash(
                    'notice',
                    'Sorry invoice is not ready!!'
                );
                return $this->redirectToRoute('submit_order');
            }



        }

        $filename = sprintf('invoice-%s.pdf', $invoiceId);
        $path = $this->container->getParameter('kernel.root_dir')."/../web/uploads/invoice/" . $filename;

        if(is_file($path)){
           unlink($path);
        }

            $pageUrl = $this->generateUrl('pdf_generate', array('invoiceId'=>$invoiceId), true); // use absolute path!
            $this->container->get('knp_snappy.pdf')->generate($pageUrl, $path);

            return new Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => sprintf('attachment; filename="%s"', $filename),
                )
            );
    }

    /**
     * @Route("/booking/cancel/{invoiceNumber}", name="booking_cancel")
     * @Security("has_role('ROLE_USER')")
     */
    public function cancelAction(Request $request, $invoiceNumber){

        $em = $this->getDoctrine()->getManager();

        $invoice = $em->getRepository('AppBundle:Invoice')->findUniqByAuthorAndId($this->getUser()->getId(), $invoiceNumber, Invoice::IS_NEW);

        if(!$invoice){
            $this->addFlash(
                'error',
                'Sorry You not have a permission for delete this invoice!'
            );
            return $this->redirectToRoute('submit_order');
        }

        $em->remove($invoice);
        $em->flush();

            $this->addFlash(
                'notice',
                "Invoice #{$invoiceNumber} has been removed!"
            );
            return $this->redirectToRoute('submit_order');
    }

    /**
     *
     * @Route("/pdf/{invoiceId}", name="pdf_generate")
     * @Template()
     * @param Request $request
     *
     * @param Request $request
     * @return array
     */
    public function pdfAction(Request $request, $invoiceId){


        $em = $this->getDoctrine()->getManager();

        $invoice = $em->getRepository('AppBundle:Invoice')->findByInvoiceIdForPdf($invoiceId);

        if(!count($invoice)){
            $this->addFlash(
                'notice',
                'Please Submit order!'
            );
            return $this->redirectToRoute('submit_order');
        }

        $userSettings = $em->getRepository('AppBundle:UserSettings')->findByUserId($invoice[0]['userId']);
        $invoiceSettings = $em->getRepository('AppBundle:InvoiceSettings')->findMax();

        return array('invoiceSettings'=>$invoiceSettings, 'invoice'=>$invoice, 'userSettings'=>$userSettings);

    }

    /**
     * @Route("/t3/{invoiceId}", name="generate_t3_statment")
     * @Security("has_role('ROLE_USER')")
     */
    public function t3Action(Request $request, $invoiceId){

        $em = $this->getDoctrine()->getManager();
        $secure = $this->container->get('security.authorization_checker');
        if(!$secure->isGranted("ROLE_SUPER_ADMIN")){

            $invoices = $em->getRepository('AppBundle:Invoice')->findUniqByAuthorAndId($this->getUser()->getId(), $invoiceId);

            if(!$invoices){
                $this->addFlash(
                    'notice',
                    'Not have a permission!'
                );
                return $this->redirectToRoute('submit_order');
            }

            if(!$invoices->getTrackNumber()){
                $this->addFlash(
                    'notice',
                    'Sorry T3 statment is not ready!!'
                );
                return $this->redirectToRoute('submit_order');
            }

        }

        $filename = sprintf('t3_statment_%s.pdf', $invoiceId);
        $path = $this->container->getParameter('kernel.root_dir')."/../web/uploads/invoice/" . $filename;

        if(is_file($path)){
            unlink($path);
        }

        $pageUrl = $this->generateUrl('t3_pdf_generate', array('invoiceId'=>$invoiceId, 'cuserId'=> $this->getUser()->getId()), true); // use absolute path!
        $this->container->get('knp_snappy.pdf')->generate($pageUrl, $path);

        return new Response(
            $this->get('knp_snappy.pdf')->getOutput($pageUrl),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => sprintf('attachment; filename="%s"', $filename),
            )
        );


    }
    /**
     *
     * @Route("/t3pdf/{invoiceId}/{cuserId}", name="t3_pdf_generate")
     * @Template()
     * @param Request $request
     *
     * @param Request $request
     * @return array
     */
    public function t3pdfAction(Request $request, $invoiceId, $cuserId){


        $em = $this->getDoctrine()->getManager();

        $invoice = $em->getRepository('AppBundle:Invoice')->findOneByNumber($invoiceId);
        $invoiceSettings = $em->getRepository('AppBundle:InvoiceSettings')->findMax();

        $userSettings = $em->getRepository('AppBundle:UserSettings')->findByUser();



        return array('invoiceSettings'=>$invoiceSettings, 'invoice'=>$invoice, 'userSettings'=>$userSettings);

    }
}
