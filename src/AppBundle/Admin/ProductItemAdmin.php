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
use Sonata\AdminBundle\Show\ShowMapper;

class ProductItemAdmin extends Admin
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
            ->add('manufacturer', 'text', array('required'=>true))
            ->add('nds', 'text', array('required'=>true))
            ->add('size', 'text', array('required'=>true))
            ->add('unit', 'choice', array('choices'=>
                array(ProductItem::CT=>'ct', ProductItem::ML=>'ml',
                    ProductItem::S_EA=>'s ea',
                    ProductItem::GM=>'gm'), 'multiple'=>false
            ), array('required'=>true))
            ->add('strength', 'text', array('required'=>false))
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
            ->add('manufacturer')
            ->add('nds')
            ->add('size')
            ->addIdentifier('unit', 'choice', array(
                'choices'  => array(ProductItem::CT=>'ct', ProductItem::ML=>'ml',
                    ProductItem::S_EA=>'s ea',
                    ProductItem::GM=>'gm',
                ),
            ))
            ->add('strength')
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
            ->add('manufacturer')
            ->add('nds')
            ->add('size')
            ->add('unit', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(ProductItem::CT=>'ct', ProductItem::ML=>'ml',
                    ProductItem::S_EA=>'s ea',
                    ProductItem::GM=>'gm'))
            )
            ->add('strength')
            ;
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('id')
            ->add('manufacturer')
            ->add('nds')
            ->add('size')
            ->add('unit', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(ProductItem::CT=>'ct', ProductItem::ML=>'ml',
                    ProductItem::S_EA=>'s ea',
                    ProductItem::GM=>'gm'))
            )
            ->add('strength')
        ;
    }


}