<?php
/**
 * Created by PhpStorm.
 * User: parem
 * Date: 1/17/17
 * Time: 1:00 PM
 */
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin as Admin;
use Sonata\AdminBundle\Form\FormMapper;

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
                    ->add('productItem', 'sonata_type_collection', array(),
                        array('edit'=>'inline', 'sortable'=>'id', 'multiple'=>false))
            ->add('alternative', 'sonata_type_model_autocomplete',
                array('property'=>'manufacturer', 'multiple'=>true,
                    'placeholder'=>'Selcet alternative'))
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

}