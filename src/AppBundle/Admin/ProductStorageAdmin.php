<?php
/**
 * Created by PhpStorm.
 * User: parem
 * Date: 1/17/17
 * Time: 1:11 PM
 */

namespace AppBundle\Admin;

use AppBundle\Entity\ProductItem;
use Sonata\AdminBundle\Admin\AbstractAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class ProductStorageAdmin extends Admin
{


    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC', // sort direction
        '_sort_by' => 'expiryDate' // field name
    );


    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->add('clone', 'clone/{objectId}/{count}');
    }

    public function getBatchActions()
    {
        // retrieve the default (currently only the delete action) actions
        $actions = parent::getBatchActions();

        // check user permissions
        if ($this->hasRoute('edit') && $this->isGranted('EDIT') && $this->hasRoute('delete') && $this->isGranted('DELETE')) {
        }

        return $actions;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Product', array(
                'class' =>'col-sm-6',
                'box-class' => 'box box-solid box-danger',
                'description'=>'Products Lot and Count'
            ))
            ->add('product', 'sonata_type_model_autocomplete', array('property' => 'name','required'=>true))
            ->add('count')
            ->add('lot', 'text', array('required'=>true, 'label'=>'Lot'))
            ->end()
            ->with('Date', array(
                'class' =>'col-sm-6',
                'box-class' => 'box box-solid box-danger',
                'description'=>'Products Sup Date and Expiry Date'
            ))
            ->add('supDate','sonata_type_date_picker', array(
                'dp_side_by_side'       => false,
                'dp_use_current'        => false,
                'widget' => 'single_text',
                'format' => 'y-dd-MM',
                'required' => false,
                'attr'=>['style' => 'width: 100px !important', 'class'=>'pull-left']
            ))
            ->add('expiryDate','sonata_type_date_picker', array(
                'dp_side_by_side'       => false,
                'dp_use_current'        => false,
                'widget' => 'single_text',
                'format' => 'y-dd-MM',
                'required' => false,
                'attr'=>['style' => 'width: 100px !important', 'class'=>'pull-left']
            ))
        ->end()
    ;

    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('id')
            ->add('product',null, ['sortable' => 'product.name','label'=>'Name'])
            ->add('count',null, ['label'=>'Count', 'editable' => true])
            ->add('lot', null, ['editable' => true])
            ->add('expiryDate', 'date', ['editable' => true])
            ->add('supDate', 'date', ['editable' => true])
            ->add('_action', 'actions',
                array('actions'=>
                    array('clone' => array('template' => 'AppBundle:CRUD:list__action_clone.html.twig'),
                        'show'=>array(), 'edit'=>array(), 'delete'=>array())
                ))
        ;

    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $products = $em->getRepository('AppBundle:Product')->fidListing();

        $filter
            ->add('id')
            ->add('product',null, array(), null, array('expanded' => false, 'multiple' => true, 'data'=>$products))
            ->add('count')
            ->add('lot', null, array('label'=>'Lot'))
            ->add('supDate', 'doctrine_orm_datetime_range', array(),'sonata_type_datetime_range_picker',
                array('field_options_start' => array('format' => 'yyyy-MM-dd HH:mm:ss'),
                    'field_options_end' => array('format' => 'yyyy-MM-dd HH:mm:ss'))
            )
            ->add('expiryDate', 'doctrine_orm_datetime_range', array(),'sonata_type_datetime_range_picker',
                array('field_options_start' => array('format' => 'yyyy-MM-dd HH:mm:ss'),
                    'field_options_end' => array('format' => 'yyyy-MM-dd HH:mm:ss'))
            )
            ;
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('id')
            ->add('product')
            ->add('count')
            ->add('lot', null, array('label'=>'Lot'))
            ->add('supDate')
            ->add('expiryDate')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object){

        $pool = $this->getConfigurationPool();
        $user = $pool->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setUser($user);

    }


    /**
     * This function
     * @param string $context
     * @return \Sonata\AdminBundle\Datagrid\ProxyQueryInterface
     */
    public function createQuery($context = 'list')
    {
        $pool = $this->getConfigurationPool();
        $user = $pool->getContainer()->get('security.token_storage')->getToken()->getUser();

        // get sonata admin pool service
        $pool = $this->getConfigurationPool();
        $query = parent::createQuery($context);

        $query->addSelect('pr, pri, prm, b, bi, pc');
        $query->leftJoin($query->getRootAlias() . '.product', 'pr');
        $query->leftJoin('pr.category', 'pc');
        $query->leftJoin('pr.productItem', 'pri');
        $query->leftJoin('pri.manufacturers', 'prm');
        $query->leftJoin($query->getRootAlias() . '.booking', 'b');
        $query->leftJoin('b.invoice', 'bi');
        $query->leftJoin($query->getRootAlias().'.user', 'u');

        if(!$this->isGranted("ROLE_SUPER_ADMIN")) {
            // create query
            $query->andWhere(
                $query->expr()->in($query->getRootAliases()[0] . '.user', ':ui')
            );$query->orWhere(
                $query->expr()->isNull($query->getRootAliases()[0] . '.user')

            );
            $query->setParameter('ui', $user);

        }

        return $query;
    }
}