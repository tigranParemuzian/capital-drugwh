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

        return array('name' => 'ssss');
    }

    /**
     * @Route("why_directrex", name="why_directrex")
     * @Template()
     */
    public function whyDirectrxAction(Request $request){

        return array('name'=>'whyDirectrx');

    }

    /**
     * @Route("products", name="products")
     * @Template()
     * @param Request $request
     */
    public function productsAction(Request $request){
        return array('name'=>'Products');
    }
}
