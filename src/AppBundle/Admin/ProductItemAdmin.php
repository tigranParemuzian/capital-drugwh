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
            ->add('unit', 'choice', array('choices'=>
                array(ProductItem::CT=>'CT', ProductItem::ML=>'ML', ProductItem::S_EA=>'S EA', ProductItem::GM=>'GM'), 'multiple'=>false
            ), array('required'=>true))
            ->add('size', 'text', array('required'=>true))
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
                'choices'  => array(ProductItem::CT=>'CT', ProductItem::ML=>'ML'),
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
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show)
    {
    }


}