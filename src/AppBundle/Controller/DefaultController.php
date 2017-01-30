<?php

namespace AppBundle\Controller;

use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Source\Source;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
//        return $this->redirect('http://www.capital-drug.com/');
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
     * @Template()
     */
    public function showSingleAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();

        $single = $em->getRepository('AppBundle:Product')->findOneBy(array('id'=>$id));

        return $this->render('@App/Default/show.html.twig', array('product'=>$single));
    }

    /**
     * @Route("medicine/{slug}", name="show_single")
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
     * @Route("my_bag", name="my_bag")
     * @Template()
     */
    public function myBagAction(Request $request){

        return array('mybag');
    }
}
