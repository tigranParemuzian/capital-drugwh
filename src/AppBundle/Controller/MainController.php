<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserSettings;
use AppBundle\Form\CreditApplicationUploadType;
use AppBundle\Form\UserSettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class MainController extends Controller
{
    /**
     * @Route("about-us", name="about_us")
     * @Template()
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAsAction(Request $request)
    {

        return array('name' => 'About As');
    }

    /**
     * @Route("why-directrex", name="why_directrex")
     * @Template()
     */
    public function whyDirectrxAction(Request $request){

        return array('name'=>'Why Direct from US');

    }

    /**
     * @Route("terms-and-conditions", name="terms_and_conditions")
     * @Template()
     */
    public function termsAndConditionsAction(Request $request){

        return array('name'=>'Terms And Conditions');

    }

    /**
     * @Route("products", name="products")
     * @Template()
     * @param Request $request
     */
    public function productsAction(Request $request){

        if(is_object($this->getUser())){
            return $this->redirect($this->generateUrl('list'));
        } else{
            $em = $this->getDoctrine()->getManager();

            $products = $em->getRepository('AppBundle:Menu')->findBy(array('slug'=>'products'));

            return array('name'=>'Products', 'products'=>$products);
        }

    }

    /**
     * @Route("services", name="services")
     * @Template()
     * @param Request $request
     */
    public function servicesAction(Request $request){
        return array('name'=>'Services');
    }

    /**
     * @Route("/user-settings", name="user_settings")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function userSettingsAction(Request $request){

        $userSettigs = $this->getUser()->getUserSettings();
        if(!$userSettigs){
            $userSettigs = new UserSettings();
        }

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new UserSettingsType(), $userSettigs);


        // determination file types
        if($request->isMethod('POST')) {

            // get request & check
            $form->handleRequest($request);
            if ($form->isValid()) {

                $em->persist($userSettigs);
                $user = $em->getRepository('AppBundle:User')->find($this->getUser()->getId());
                $user->setUserSettings($userSettigs);
                $em->persist($userSettigs);
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('user_settings_show'));
            }
        }
        // return data
        return array('form' => $form->createView()) ;
    }

    /**
     * @Route("/credit-application-show", name="user_settings_show")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function userSettingsShowAction(Request $request){

        $userSettings = $this->getUser()->getUserSettings();

        if(!$userSettings) {
            $this->addFlash(
                'error',
                'Please add Credit Application and Agreement informations.!'
            );
            return $this->redirect($this->generateUrl('user_settings'));
        }
            $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new CreditApplicationUploadType());


        // determination file types
        if($request->isMethod('POST')) {

            // get request & check
            $form->handleRequest($request);
            if ($form->isValid()) {

                $data = $form->getData();
                $file = $data['file'];

                if (is_file($file) && in_array($file->getMimeType(), array('application/pdf')))
                {
                    $userSettings->setFile($file);
                    $userSettings->uploadFile();
                    $em->persist($userSettings);
                    $em->flush();

                    $this->addFlash(
                        'notice',
                        'Credit Application file file successfully uploaded.'
                    );
                }
            }

        }

        return array('userSettings'=>$userSettings, 'form' => $form->createView());
    }

    /**
     * @Route("credit-application", name="credit_application_crete")
     * @Security("has_role('ROLE_USER')")
     */
    public function creditApplicationAction(Request $request){

        $userSettings = $this->getUser()->getUserSettings();

        if(!$userSettings){
            $this->addFlash(
                'error',
                'Please type form!'
            );
            return $this->redirectToRoute('user_settings');
        }

        $filename = sprintf('credit-application-%s.pdf', $this->getUser()->getId());
        $path = $this->container->getParameter('kernel.root_dir')."/../web/uploads/credit_application/" . $filename;

        if(is_file($path)){
            unlink($path);
        }

            $pageUrl = $this->generateUrl('credit_pdf', array('userSettings'=>$userSettings->getId()), true); // use absolute path!
            $this->container->get('knp_snappy.pdf')->generate($pageUrl, $path, array('orientation'=>'Portrait'));

            return new Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl, array('orientation'=>'Portrait')),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => sprintf('attachment; filename="%s"', $filename),
                )
            );
    }

    /**
     * @Route("/credit/pdf/{userSettings}", name="credit_pdf")
     * @Template()
     */
    public function creditPdfAction($userSettings){
        $em = $this->getDoctrine()->getManager();

        $userSettings = $em->getRepository('AppBundle:UserSettings')->find($userSettings);

        if(!$userSettings){
            $this->addFlash(
                'error',
                'Please type form!'
            );
            return $this->redirectToRoute('user_settings');
        }

        return array('userSettings'=>$userSettings);

    }

    /**
     * @param Request $request
     * @Route("/credit-application/{userSettId}", name="credit-application")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function downloadCreditApplication(Request $request, $userSettId){

        $em = $this->getDoctrine()->getManager();

        $usersettings = $em->getRepository('AppBundle:UserSettings')->find($userSettId);
        if(!$usersettings || strlen($usersettings->getFileName()) <0 ){

            return $this->redirect($this->generateUrl('user_settings'));
        }

        $file = $usersettings->getDownloadLink();
        $response = new BinaryFileResponse($this->container->getParameter('kernel.root_dir')."/../web".$file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    /**
     *
     * @Route("/packing-slip/{invoiceNumber}", name="packing_slip")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     *
     */
    public function packingSlipAction(Request $request, $invoiceNumber){

        $filename = sprintf('packing-slip-%s.pdf', $invoiceNumber);
        $path = $this->container->getParameter('kernel.root_dir')."/../web/uploads/packing_slip/" . $filename;

        if(is_file($path)){
            unlink($path);
        }

        $pageUrl = $this->generateUrl('pdf_generate_slip', array('invoiceId'=>$invoiceNumber), true); // use absolute path!
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
     * @Route("/slip/pdf/{invoiceId}", name="pdf_generate_slip")
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
     * @param Request $request
     * @Route("/send-email/{invoiceNumber}", name="send_email")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function sendEmailAction(Request $request, $invoiceNumber){

       $this->sendEmail2($invoiceNumber, null, 'RXtrace@aamedllc.com');

        return $this->redirect($this->generateUrl('admin_app_invoice_list'));
    }



    public function sendEmail2($invoiceNumber, $state = null, $emailFrom = null)
    {

        $em = $this->container->get('doctrine')->getManager();

        $containerAdmin = $this->container;

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
                        '<p>Please find attached Invoice and  T3 statement for your records, if you have any questions, please feel free to contact us.</p>'.
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


    public function sendEmail($invoiceNumber){

        $em = $this->getDoctrine()->getManager();

        $userEmails = $em->getRepository('AppBundle:Invoice')->findUserInfo($invoiceNumber);

        if(!$userEmails){

            $this->addFlash(
                'error',
                "Sorry Invoice by invoice number {$invoiceNumber} not found."
            );
        return $this->redirect($this->generateUrl('admin_app_invoice_list'));
        }

        try{

            $filename = sprintf('invoice-%s.pdf', $invoiceNumber);
            $pathInv = $this->container->getParameter('kernel.root_dir')."/../web/uploads/invoice/" . $filename;

            if(is_file($pathInv)){
                unlink($pathInv);
            }

            $pageUrl = $this->generateUrl('pdf_generate', array('invoiceId'=>$invoiceNumber), true); // use absolute path!
            $this->container->get('knp_snappy.pdf')->generate($pageUrl, $pathInv);

            $filename = sprintf('t3_statment_%s.pdf', $invoiceNumber);
            $path = $this->container->getParameter('kernel.root_dir')."/../web/uploads/invoice/" . $filename;

            if(is_file($path)){
                unlink($path);
            }

            $pageUrl = $this->generateUrl('t3_pdf_generate', array('invoiceId'=>$invoiceNumber, 'cuserId'=>1), true); // use absolute path!
            $this->container->get('knp_snappy.pdf')->generate($pageUrl, $path);

            $email = array();
            foreach ($userEmails->getUser()->getUserEmails() as $emails){
                $email[] = $emails;
            }

            $message = \Swift_Message::newInstance()
                ->setSubject("Order Invoice & T3 {$invoiceNumber}" )
                ->setFrom('info@aamedllc.com')
                ->setTo("{$email[0]}");
            for ($i = 1; $i<count($email); $i++){
                $message
                    ->addCc("{$email[$i]}");
            }
            $message->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    '@App/Main/email_content.html.twig',
                    array('name' => 't3_o')
                ),
                'text/html'
            )
                ->attach(\Swift_Attachment::fromPath($pageUrl))
                ->attach(\Swift_Attachment::fromPath($path));
            $this->get('mailer')->send($message);

        } catch (\Swift_Message $exception){

            $this->addFlash(
                'error',
                "Sorry Invoice by invoice number {$invoiceNumber} not found."
            );

        }
    }

    /**
     *
     * @Route("/product/prices/{productId}", name="product-prices")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @param $prId
     */
    public function priceAction(Request $request, $productId){

        $em = $this->getDoctrine()->getManager();

        if(!is_null($this->getUser()->getSalePercent())){

            $price = $em->getRepository('AppBundle:UserPrice')->findByUserPrices($this->getUser()->getId(), $productId);

            if(!$price){

                $product = $em->getRepository('AppBundle:Product')->find((int)$productId)->getWacPkgPrice();

                $price = $product - ($product * $this->getUser()->getSalePercent() /100);
            }else {
                $price['individualPrice'] ? $price = $price['individualPrice'] : $price = $price['price'] - ($price['price'] * $price['percent']/100);
            }
        }else {
            $price = $em->getRepository('AppBundle:Product')->find((int)$productId)->getWacPkgPrice();
        }

        return $this->render('@App/Main/price.html.twig', ['price'=>$price]);
    }
}
