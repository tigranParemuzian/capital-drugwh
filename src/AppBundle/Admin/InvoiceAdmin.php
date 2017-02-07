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
        $collection->clearExcept(array('list', 'delete', 'edit', 'batch'));
        $collection->add('invoice_pdf');
        $collection->add('t3_statement_pdf');

    }


    # Override to add actions like delete, etc...
    public function getBatchActions()
    {
        // retrieve the default (currently only the delete action) actions
        $actions = parent::getBatchActions();

        // check user permissions
        if($this->hasRoute('edit') && $this->isGranted('EDIT') && $this->hasRoute('delete') && $this->isGranted('DELETE'))
        {
            // define calculate action
            $actions['invoice_pdf']= array ('label' => 'Generate Pdf', 'ask_confirmation'  => true );
            $actions['t3_statement_pdf']= array ('label' => 'T3 Statement Pdf', 'ask_confirmation'  => true );

        }

        return $actions;
    }


    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main', array(
                    'class' =>'col-sm-12',
                    'box-class' => 'box box-solid box-danger',
                    'description'=>'Categiry main create part'
                ))
                    ->add('number')
                    ->add('total')
                    ->add('status', 'choice', array('choices'=>
                array(Invoice::IS_NEW=>'New', Invoice::IN_PROGRESS=>'On Progress',
                    Invoice::IS_SHIPPED=>'Shipped'), 'multiple'=>false
            ), array('required'=>true))
                    ->add('user')
            ->end()
            ->with('Bookings', array('class' =>'col-sm-12',
                'box-class' => 'box box-solid box-danger',
                'description'=>'Bookings part'))
            ->add('booking', 'sonata_type_collection', array(), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'id',
                    'delete'=>true
            )
               )
            ->end()
            ;

    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('number', 'text')
            ->add('total')
            ->add('status', 'choice', array('choices'=>
                array(Invoice::IS_NEW=>'New', Invoice::IN_PROGRESS=>'On Progress',
                    Invoice::IS_SHIPPED=>'Shipped'), 'editable'=>true
            ))
            ->add('user')
            ->add('created')
            ->add('_action', 'actions',
                array('actions'=>
                    array(
                        'invoice_pdf' => array('template' => 'AppBundle:CRUD:generate_pdf.html.twig'),
                        't3_statement_pdf' => array('template' => 'AppBundle:CRUD:t3_statment_pdf.html.twig'),
                        'delete'=>array(), 'edit'=>array()
                    )
                ))
        ;

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('number')
            ->add('total')
            ->add('status', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(Invoice::IS_NEW=>'New', Invoice::IN_PROGRESS=>'On Progress',
                    Invoice::IS_SHIPPED=>'Shipped'))
            )
            ->add('user')
            ->add('created', 'doctrine_orm_datetime_range', array(),'sonata_type_datetime_range_picker',
                array('field_options_start' => array('format' => 'yyyy-MM-dd HH:mm:ss'),
                    'field_options_end' => array('format' => 'yyyy-MM-dd HH:mm:ss'))
            )
//            ->add('updated', 'doctrine_orm_datetime_range', array(),'sonata_type_datetime_range_picker',
//                array('field_options_start' => array('format' => 'yyyy-MM-dd HH:mm:ss'),
//                    'field_options_end' => array('format' => 'yyyy-MM-dd HH:mm:ss'))
//            )
        ;
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
                'choice', array('choices' =>array(Invoice::IS_NEW=>'New', Invoice::IN_PROGRESS=>'On Progress',
                Invoice::IS_SHIPPED=>'Shipped'))
            )
            ->add('booking')
        ->add('user')

            ->add('created')
//            ->add('updated')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
//        dump($object->getBooking()); exit;
        if($object->getBooking()){
            foreach($object->getBooking() as $productIngredient) {
                $productIngredient->setInvoice($object);
                $productIngredient->setClient($object->getUser());
                $productIngredient->setCost(round($productIngredient->getProduct()->getPrice()*$productIngredient->getCount(), 2));
                $productIngredient->setSubTotal(round($productIngredient->getProduct()->getPrice()*$productIngredient->getCount(), 2));
                $productIngredient->setStatus(Booking::IS_ORDERED);
            }
        }
//        $object->uploadFile();
    }
    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        if($object->getBooking()){
//            dump($object->getBooking()); exit;
            foreach($object->getBooking() as $productIngredient) {
                $productIngredient->setInvoice($object);
                $productIngredient->setClient($object->getUser());
                $productIngredient->setCost(round($productIngredient->getProduct()->getPrice()*$productIngredient->getCount(), 2));
                $productIngredient->setSubTotal(round($productIngredient->getProduct()->getPrice()*$productIngredient->getCount(), 2));
                $productIngredient->setStatus(Booking::IS_ORDERED);
            }
        }
//        $object->uploadFile();
    }

//    public function batchActionCalculate(ProxyQueryInterface $selectedModelQuery)
//    {
//        ...
//    }
}