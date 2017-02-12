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

class UserEmailsAdmin extends Admin
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
            ->add('email', 'email')
            ->add('status', 'choice', array('choices'=>
                array(true=>'Enable', false=>'Disable')
            ))
//            ->add('user')
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
            ->add('email')
            ->add('status', 'choice', array('choices'=>
                array(true=>'Enable', false=>'Disable'), 'editable'=>true
            ))
            ->add('user')
            ->add('_action', 'actions',
                array('actions'=>
                    array(
                        'show'=>array(), 'edit'=>array(), 'delete'=>array())
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
            ->add('email')
            ->add('status', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(true=>'Enable', false=>'Disable'))
            )
            ->add('user')
            ;
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('id')
            ->add('email')
            ->add('status', 'choice', array('choices'=>
                array(true=>'Enable', false=>'Disable'), 'editable'=>true
            ))
            ->add('user')
        ;
    }
}