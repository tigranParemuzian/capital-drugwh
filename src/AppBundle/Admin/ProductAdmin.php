<?php
/**
 * Created by PhpStorm.
 * User: parem
 * Date: 1/17/17
 * Time: 1:00 PM
 */
namespace AppBundle\Admin;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductItem;
use Sonata\AdminBundle\Admin\AbstractAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProductAdmin extends Admin
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
                    ->add('name')
                    ->add('productItem','sonata_type_model', array('expanded' => false,
                                                                    'by_reference' => true,
                                                                    'btn_add'=>true,
                                                                    'multiple' => false, 'sortable'      => 'ordering',))
            ->add('alternative', 'text', array('required'=>false))
            ->add('count', 'number', array('required'=>false))
            ->add('price', 'text', array('required'=>true))
            ->add('pricingCode', 'text', array('required'=>false))
            ->add('avalible', 'choice', array('choices'=>
                array(1=>'avalible', 2=>'Not avalible'), 'multiple'=>false
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
            ->add('name')
            ->add('productItem')
            ->add('alternative')
            ->addIdentifier('count')
            ->addIdentifier('price')
            ->addIdentifier('avalible', 'choice', array(
                    'choices'  => array(1=> 'Avalible',
                        2=>'Not avalible'),
                ))
            ->add('created')
            ->add('updated')
            ->add('_action', 'actions',
                array('actions'=>
                    array('show'=>array(), 'edit'=>array(), 'delete'=>array())
                ))
        ;

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('avalible', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(1=> 'Avalible',
                    2=>'Not avalible')))
            ->add('alternative', null, array(''))
            ->add('count')
            ->add('price')
            ->add('productItem', null, array(), 'entity', array(
                'class'    => 'AppBundle\Entity\ProductItem',
                'property' => 'Manufacturer',
            ))
            ->add('created', 'doctrine_orm_datetime_range', array(),'sonata_type_datetime_range_picker',
                array('field_options_start' => array('format' => 'yyyy-MM-dd HH:mm:ss'),
                    'field_options_end' => array('format' => 'yyyy-MM-dd HH:mm:ss'))
            )
            ->add('updated', 'doctrine_orm_datetime_range', array(),'sonata_type_datetime_range_picker',
                array('field_options_start' => array('format' => 'yyyy-MM-dd HH:mm:ss'),
                    'field_options_end' => array('format' => 'yyyy-MM-dd HH:mm:ss'))
            );
        ;
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
        ->add('id')
        ->add('name')
        ->add('avalible', 'doctrine_orm_choice', array(),
            'choice', array('choices' => array(ProductItem::CT=> 'CT',
                ProductItem::ML=>'ML')))
        ->add('alternative', null, array(''))
        ->add('count')
        ->add('price')
        ->add('productItem', null, array(), 'entity', array(
            'class'    => 'AppBundle\Entity\ProductItem',
            'property' => 'Manufacturer',
        ))
            ->add('created')
            ->add('updated');
    }

}