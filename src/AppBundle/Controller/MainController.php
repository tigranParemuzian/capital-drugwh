<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserSettings;
use AppBundle\Form\UserSettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        return array('name'=>'Why Directrx');

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

        if(!$userSettings){
            $this->addFlash(
                'error',
                'Please add Credit Application and Agreement informations.!'
            );
            return $this->redirect($this->generateUrl('user_settings'));

        }

        return array('userSettings'=>$userSettings);
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
            $this->container->get('knp_snappy.pdf')->generate($pageUrl, $path);

//        return new BinaryFileResponse($path);
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

}
