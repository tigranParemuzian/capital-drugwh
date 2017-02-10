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
     * @Route("/user-settings-show", name="user_settings_show")
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
                // corrections of uploaded file name because is sended from cli
//                $fileName = $this->getUser()->getId().(str_replace( ' ', '_', str_replace('(', '_', str_replace(')', '_', $file->getClientOriginalName()))));
                // save file in /web/uploads/files folder
//                $brochuresDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/credit_application_uploads';
//                $mainDir = str_replace('/app', '/', $this->container->getParameter('kernel.root_dir'));
//                $form->get('file');
                if (is_file($file) && in_array($file->getMimeType(), array('application/pdf')))
                {
                    // move file to uploda directory
//                    $file->move($brochuresDir, $fileName);

//                    $file = $brochuresDir.'/'.$fileName;

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

//        return new BinaryFileResponse($path);
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

}
