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

class MenuAdmin extends Admin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main', array(
                'class' =>'col-sm-6',
                'box-class' => 'box box-solid box-danger',
                'description'=>'Main information'
            ))
            ->add('name', 'text', array('required'=>true))
            ->add('position', 'number', array('required'=>true))
            ->add('status', 'choice', array('choices'=>
                array(1=>'On', 0=>'Off'), 'multiple'=>false
            ), array('required'=>true))
            ->end()
            ->with('Parent', array(
                'class' =>'col-sm-6',
                'box-class' => 'box box-solid box-danger',
                'description'=>'Parent information'
            ))
            ->add('menuItoms', null, array('required'=>false))
            ->add('metaTitle', 'text', array('required'=>true))
            ->add('metaDescription', 'text', array('required'=>true))
        ->end()
    ;

    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('name')
            ->addIdentifier('position')
            ->add('status', 'choice', array('choices'=>
                array(1=>'On', 0=>'Off'), 'editable'=>true
            ))
            ->add('menuItoms')
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
            ->add('name')
            ->add('position')
            ->add('status', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(1=>'On', 0=>'Off')))
            ->add('menuItoms')
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
            ->add('position')
            ->add('status', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(1=>'On', 0=>'Off')))
            ->add('menuItoms')
        ;
    }


}