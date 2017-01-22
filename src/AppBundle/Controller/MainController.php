<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @Route("about_us", name="about_us")
     * @Template()
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAsAction($name)
    {

        return $this->render('', array('name' => $name));
    }
}
