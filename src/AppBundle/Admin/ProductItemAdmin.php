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
            ->add('manufacturers', 'sonata_type_model_autocomplete', array('property' => 'name','required'=>true))
            ->add('nds', 'text', array('required'=>true, 'label'=>'NDC'))
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
            ->add('product.name',null, ['label'=>'Name'])
            ->add('manufacturer',null, ['label'=>'manufacturer'])
            ->add('strength')
            ->add('nds', null, array('label'=>'NDC'))
            ->add('size')
            ->addIdentifier('unit', 'choice', array(
                'choices'  => array(ProductItem::CT=>'ct', ProductItem::ML=>'ml',
                    ProductItem::S_EA=>'s ea',
                    ProductItem::GM=>'gm',
                ),
            ))
            ->add('product.count',null, ['label'=>'Count'])
            ->add('product.price', null, ['label'=>'$ Show Price'])
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
            ->add('strength')
            ->add('nds', null, array('label'=>'NDC'))
            ->add('size')
            ->add('unit', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(ProductItem::CT=>'ct', ProductItem::ML=>'ml',
                    ProductItem::S_EA=>'s ea',
                    ProductItem::GM=>'gm'))
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
            ->add('manufacturer')
            ->add('nds', null, array('label'=>'NDC'))
            ->add('size')
            ->add('unit', 'doctrine_orm_choice', array(),
                'choice', array('choices' => array(ProductItem::CT=>'ct', ProductItem::ML=>'ml',
                    ProductItem::S_EA=>'s ea',
                    ProductItem::GM=>'gm'))
            )
            ->add('strength')
        ;
    }

//manufacturer

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        if ($object->getManufacturers()) {

            $object->setManufacturer($object->getManufacturers()->getName());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        if ($object->getManufacturers()) {

            $object->setManufacturer($object->getManufacturers()->getName());
        }
    }

}