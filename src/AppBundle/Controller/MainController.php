<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("about_us", name="about_us")
     * @Template()
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAsAction(Request $request)
    {

        return array('name' => 'About As');
    }

    /**
     * @Route("why_directrex", name="why_directrex")
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
        return array('name'=>'Products');
    }

    /**
     * @Route("services", name="services")
     * @Template()
     * @param Request $request
     */
    public function servicesAction(Request $request){
        return array('name'=>'Services');
    }
}
