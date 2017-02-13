<?php
/**
 * Created by PhpStorm.
 * User: parem
 * Date: 1/17/17
 * Time: 1:11 PM
 */

namespace AppBundle\Admin;

use AppBundle\Entity\Booking;
use AppBundle\Entity\ProductItem;
use Sonata\AdminBundle\Admin\AbstractAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class BookingAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Main')
            ->with('Main', array(
                'class' =>'col-sm-12',
                'box-class' => 'box box-solid box-danger',
                'description'=>'Products main create part'
            ))
            ->add('product', 'sonata_type_model_autocomplete',
                array('property' => 'name','required'=>true))
            ->add('count')
            ->add('cost', null, array('required'=>false, 'label'=>'Cost $'))
            ->add('lot', null, array('required'=>false))
            ->add('expiryDate','sonata_type_date_picker', array(
                'dp_side_by_side'       => false,
                'dp_use_current'        => false,
                'widget' => 'single_text',
                'format' => 'y-dd-MM',
                'required' => true,
                'attr'=>['style' => 'width: 100px !important']
            ))
            ->add('shipDate','sonata_type_date_picker', array(
                'dp_side_by_side'       => false,
                'dp_use_current'        => false,
                'widget' => 'single_text',
                'format' => 'y-dd-MM',
                'required' => true,
                'label'=>'SUP date',
                'attr'=>['style' => 'width: 100px !important']
            ))
//            ->add('client', null, array('required'=>false))
//            ->add('invoice', 'text', array('required'=>true))
            ->add('status', 'choice', array('choices'=>
                array(Booking::IS_NEW=>'New', Booking::IS_ORDERED=>'In order',
                    Booking::IS_CHANGED=>'Changed'), 'multiple'=>false
            ), array('required'=>false))
        ->end()
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
            ->add('invoice')
            ->add('product')
            ->add('client')
            ->add('count')
            ->add('cost')
            ->addIdentifier('status', 'choice', array(
                'choices'  =>  array(Booking::IS_ORDERED=>'In order', Booking::IS_NEW=>'New',
                    Booking::IS_CHANGED=>'Changed'),
            ))
            ->add('_action', 'actions',
                array('actions'=>
                    array('show'=>array(), 'edit'=>array(), 'delete'=>array())
                ))
        ;

    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('id')
            ->add('invoice')
            ->add('product')
            ->add('client')
            ->add('count')
            ->add('cost')
            ->add('status', 'doctrine_orm_choice', array(),
                'choice', array('choices'  =>  array(Booking::IS_NEW=>'New', Booking::IS_ORDERED=>'In order',
                    Booking::IS_CHANGED=>'Changed'))
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
            ->add('client')
            ->add('count')
            ->add('cost')
            ->add('status', 'doctrine_orm_choice', array(),
                'choice', array('choices' =>  array(Booking::IS_NEW=>'New', Booking::IS_ORDERED=>'In order',
                    Booking::IS_CHANGED=>'Changed'))
            )
        ;
    }


}