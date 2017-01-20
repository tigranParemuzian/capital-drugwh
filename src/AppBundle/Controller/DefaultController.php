<?php

namespace AppBundle\Controller;

use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Column\DateColumn;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Export\CSVExport;
use APY\DataGridBundle\Grid\Source\Source;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     */
    public function listAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->fidListing();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return array('products'=>$products, 'categories'=>$categories);

    }

    /**
     * @Route("/list_old", name="list_old")
     * @Template()
     */
    public function listingOldAction(Request $request){

        /*$gridBuilder = $this->createGridBuilder(new Entity('AppBundle:Product'), [
            'persistence'  => true,
            'route'        => 'list',
            'filterable'   => true,
            'sortable'     => true,
            'max_per_page' => 20,
        ]);

        // Creates columns
        $grid = $gridBuilder
            ->add('id', 'number', [
                'title'   => 'Id',
                'primary' => 'true',
            ])
            ->add('name', 'text', ['title'=>'Name', 'class'=>'aaaaaa'])
            ->add('created', 'datetime', [
                'field' => 'created',
            ])
            ->add('productItem.nds', 'text', ['title'=>'NDS'])
            ->getGrid();
//dump(get_class_methods($gridBuilder)); exit;
        // Handles filters, sorts, exports, ...
        $grid->handleRequest($request);

        // Renders the grid
        return $this->render('AppBundle:Default:my_grid.html.twig', ['grid' => $grid]);*/

        $source = new Entity('AppBundle:Product');

        /* @var $grid \APY\DataGridBundle\Grid\Grid */
        $grid = $this->get('grid');
        $grid->setSource($source);
        $grid->addExport(new CSVExport('CSV Export', 'export'));
        $grid->addMassAction(new DeleteMassAction());
        $MyColumn = new BlankColumn(array('filterable'=>true, 'source'=>'My Data', 'values'=>array('a', 'b', 'd'), 'isAggregate'=>true, 'id' => 'myBlankColumn', 'title' => 'CS', 'size' => '54'));
        $grid->addColumn($MyColumn);
        $MyTypedColumn = new DateColumn(array('id' => 'myTypedColumn', 'title' => 'My fsd Column', 'source' => false, 'filterable' => false, 'sortable' => false));
        $grid->addColumn($MyTypedColumn);
//        $grid->setHiddenColumns('numberOfOrders');
        return $grid->getGridResponse('AppBundle:Default:my_grid.html.twig');
    }

    /**
     * @return GridBuilder
     */
    public function createGridBuilder(Source $source = null, array $options = [])
    {
        return $this->container->get('apy_grid.factory')->createBuilder('grid', $source, $options);
    }
}
