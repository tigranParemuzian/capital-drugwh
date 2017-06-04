<?php
/**
 * Created by PhpStorm.
 * User: parem
 * Date: 1/17/17
 * Time: 1:00 PM
 */
namespace AppBundle\Admin;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductItem;
use Sonata\AdminBundle\Admin\AbstractAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class InvoiceAdmin extends Admin
{

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'delete', 'edit', 'batch', 'export'));
        $collection->add('invoice_pdf');
        $collection->add('t3_statement_pdf');
        $collection->add('packing_slip');
        $collection->add('send_email');

    }


    # Override to add actions like delete, etc...
    public function getBatchActions()
    {
        // retrieve the default (currently only the delete action) actions
        $actions = parent::getBatchActions();

        // check user permissions
        if ($this->hasRoute('edit') && $this->isGranted('EDIT') && $this->hasRoute('delete') && $this->isGranted('DELETE')) {
            // define calculate action
            $actions['invoice_pdf'] = array('label' => 'Generate Pdf', 'ask_confirmation' => true);
            $actions['t3_statement_pdf'] = array('label' => 'T3 Statement Pdf', 'ask_confirmation' => true);
            $actions['packing_slip'] = array('label' => 'Packing Slip Pdf', 'ask_confirmation' => true);
            $actions['send_email'] = array('label' => 'Send Email', 'ask_confirmation' => true);

        }

        return $actions;
    }


    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main', array(
                'class' => 'col-sm-12',
                'box-class' => 'box box-solid box-danger',
                'description' => 'Categiry main create part'
            ))
            ->add('number')
            ->add('trackNumber', 'text', array('label' => 'Tracking Number'))
            ->add('total')
            ->add('status', 'choice', array('choices' =>
                array(Invoice::IS_NEW => 'New', Invoice::IN_PROGRESS => 'On Progress',
                    Invoice::IS_SHIPPED => 'Shipped'), 'multiple' => false
            ), array('required' => true))
            ->add('user')
            ->add('shippingHandling','sonata_type_date_picker', array(
                'dp_side_by_side'       => false,
                'dp_use_current'        => false,
                'widget' => 'single_text',
                'format' => 'y-dd-MM',
                'required' => false,
                'label'=>'Exp. Date',
                'attr'=>['style' => 'width: 100px !important']
            ))
            ->end()
            ->with('Bookings', array('class' => 'col-sm-12',
                'box-class' => 'box box-solid box-danger',
                'description' => 'Bookings part'))
            ->add('booking', null, array('label'=>'Bookngs')
            )
            ->end();

    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('number', 'text')
            ->add('trackNumber', 'text', array('label' => 'Tracking Number'))
            ->add('total')
            ->add('status', 'choice', array('choices' =>
                array(Invoice::IS_NEW => 'New', Invoice::IN_PROGRESS => 'On Progress',
                    Invoice::IS_SHIPPED => 'Shipped'), 'editable' => true
            ))
            ->add('user')
            ->add('shippingHandling', null, array('label'=>'Exp. Date'))
            ->add('created', 'date', array('date_format' => 'yyyy-MM-dd'))
            ->add('_action', 'actions',
                array('actions' =>
                    array(
                        'invoice_pdf' => array('template' => 'AppBundle:CRUD:generate_pdf.html.twig'),
                        't3_statement_pdf' => array('template' => 'AppBundle:CRUD:t3_statment_pdf.html.twig'),
                        'packing_slip' => array('template' => 'AppBundle:CRUD:packing_slip.html.twig'),
                        'send_email' => array('template' => 'AppBundle:CRUD:send_email.html.twig'),
                        'delete' => array(), 'edit' => array()
                    )
                ));

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('number')
            ->add('trackNumber', null, array('label' => 'Tracking Number'))
            ->add('total')
            ->add('status', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(Invoice::IS_NEW => 'New', Invoice::IN_PROGRESS => 'On Progress',
                    Invoice::IS_SHIPPED => 'Shipped'))
            )
            ->add('user')
            ->add('created', 'doctrine_orm_datetime_range', array(), 'sonata_type_datetime_range_picker',
                array('field_options_start' => array('format' => 'yyyy-MM-dd HH:mm:ss'),
                    'field_options_end' => array('format' => 'yyyy-MM-dd HH:mm:ss'))
            );
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('number', 'text')
            ->add('total')
            ->add('status',
                'choice', array('choices' => array(Invoice::IS_NEW => 'New', Invoice::IN_PROGRESS => 'On Progress',
                    Invoice::IS_SHIPPED => 'Shipped'))
            )
            ->add('booking')
            ->add('user')
            ->add('created');
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $total = 0;
        if ($object->getBooking()) {
            foreach ($object->getBooking() as $productIngredient) {
                $productIngredient->setInvoice($object);
                $productIngredient->setClient($object->getUser());
                $productIngredient->setCost(round($productIngredient->getProduct()->getPrice() * $productIngredient->getCount(), 2));
                $productIngredient->setSubTotal(round($productIngredient->getProduct()->getPrice() * $productIngredient->getCount(), 2));
                $productIngredient->setStatus(Booking::IS_ORDERED);
                $total += round($productIngredient->getProduct()->getPrice() * $productIngredient->getCount(), 2);
            }
        }

        if ($object->getStatus() === Invoice::IS_SHIPPED && $object->getEmailSended() == 0) {
            $object->setTotal($total);
            $object->setEmailSended(1);
            $this->sendEmail($object->getNumber(), null, 'RXtrace@aamedllc.com');
        }

        if(!is_null($object->getTrackNumber()) && $object->getEmailTracking() == 0){
            $this->sendEmail($object->getNumber(), $object->getTrackNumber(), 'info@aamedllc.com');
            $object->setEmailTracking(1);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        if ($object->getBooking()) {
            foreach ($object->getBooking() as $productIngredient) {
                $productIngredient->setInvoice($object);
                $productIngredient->setClient($object->getUser());
                $productIngredient->setCost(round($productIngredient->getProduct()->getPrice() * $productIngredient->getCount(), 2));
                $productIngredient->setSubTotal(round($productIngredient->getProduct()->getPrice() * $productIngredient->getCount(), 2));
                $productIngredient->setStatus(Booking::IS_ORDERED);
            }
        }
    }

    public function sendEmail($invoiceNumber, $state = null, $emailFrom = null)
    {

        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $containerAdmin = $this->getConfigurationPool()->getContainer();

        $userEmails = $em->getRepository('AppBundle:Invoice')->findUserInfo((string)$invoiceNumber);

        $email = array();
        foreach ($userEmails->getUser()->getUserEmails() as $emails) {
            $email[] = $emails;
        }


        if (!$userEmails) {
        } else {

            try {

                if(!is_null($state)){
                    $states = explode(',', $state);
                    $mess = '';
                    foreach ($states as $state){
                        $mess.='<a href="https://www.fedex.com/apps/fedextrack/?tracknumbers='.$state.'" target="_blank">'.$state.'</a>&nbsp;&nbsp;'   ;

                    }

                    $message = \Swift_Message::newInstance()
                        ->setSubject("Your order has been shipped")
                        ->setFrom($emailFrom)
                        ->setTo("{$email[0]}");
                    for ($i = 1; $i < count($email); $i++) {
                        $message
                            ->addCc("{$email[$i]}");
                    }

                    $message->setBody(
                        '<p>Your order has been shipped FedEx '.$mess.'<br>Thank you.</p>',
                        'text/html'
                    )
                        ;
                    $containerAdmin->get('mailer')->send($message);
                }else {
                    $filename = sprintf('invoice-%s.pdf', $invoiceNumber);
                    $pathInv = $containerAdmin->getParameter('kernel.root_dir') . "/../web/uploads/invoice/" . $filename;

                    if (is_file($pathInv)) {
                        unlink($pathInv);
                    }

                    $pageUrl = $containerAdmin->get('router')->generate('pdf_generate', array('invoiceId' => $invoiceNumber), true); // use absolute path!
                    $containerAdmin->get('knp_snappy.pdf')->generate($pageUrl, $pathInv);

                    $filename = sprintf('t3_statment_%s.pdf', $invoiceNumber);
                    $path = $containerAdmin->getParameter('kernel.root_dir') . "/../web/uploads/invoice/" . $filename;

                    if (is_file($path)) {
                        unlink($path);
                    }

                    $pageUrl = $containerAdmin->get('router')->generate('t3_pdf_generate', array('invoiceId' => $invoiceNumber, 'cuserId' => 1), true); // use absolute path!
                    $containerAdmin->get('knp_snappy.pdf')->generate($pageUrl, $path);

                    $message = \Swift_Message::newInstance()
                        ->setSubject("Order Invoice & T3 {$invoiceNumber}")
                        ->setFrom($emailFrom)
                        ->setTo("{$email[0]}");
                    for ($i = 1; $i < count($email); $i++) {
                        $message
                            ->addCc("{$email[$i]}");
                    }

                    $message->setBody(
                        '<p>Please finde attached Invoice and  T3 statement for your records, if you have any questions, please feel free to contact us.</p>'.
                        '<br><b>Thank you</b>',
                        'text/html'
                    )
                        ->attach(\Swift_Attachment::fromPath($pathInv))
                        ->attach(\Swift_Attachment::fromPath($path));
                    $containerAdmin->get('mailer')->send($message);
                }


            } catch (\Swift_Message $exception) {
//                Your order has been shipped, 7777792929292
                /*$this->addFlash(
                    'error',
                    "Sorry Invoice by invoice number {$invoiceNumber} not found."
                );*/

            }
        }
    }
}