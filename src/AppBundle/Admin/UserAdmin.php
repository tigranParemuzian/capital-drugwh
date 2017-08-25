<?php
/**
 * Created by PhpStorm.
 * User: aram
 * Date: 12/29/15
 * Time: 12:13 PM
 */

namespace AppBundle\Admin;

use AppBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin as Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class UserAdmin
 * @package AppBundle\Admin
 */
class UserAdmin extends Admin
{
//    /**
//     * @return \Symfony\Component\Form\FormBuilder
//     */
//    public function getFormBuilder()
//    {
//        $this->formOptions['data_class'] = $this->getClass();
//
//        $options = $this->formOptions;
//        $options['validation_groups'] = "Admin";
//
//        $formBuilder = $this->getFormContractor()->getFormBuilder( $this->getUniqid(), $options);
//
//        $this->defineFormBuilder($formBuilder);
//
//        return $formBuilder;
//    }

//    public function prePersist($object)
//    {
//        parent::prePersist($object);
//
//        $object->addRole("ROLE_ADMIN");
//        $object->addRole("ROLE_SUPER_ADMIN");
//        $object->addRole("ROLE_SONATA_ADMIN");
//
//        $this->updatePassword($object);
//    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('email')
            ->add('username')
            ->add('lastName')
            ->add('firstName')
            ->add('enabled')
            ->add('isValid', null, array('label'=>'Valid'))
            ->add('phone')
            ->add('roles', 'doctrine_orm_string', array(), 'choice', array(
                'choices'  => array('ROLE_USER'=>'User', 'ROLE_MODERATOR'=>'Worker',
                    'ROLE_SUPER_ADMIN'=>'Admin'),
                ))
            ->add('created', 'doctrine_orm_datetime_range', array(),'sonata_type_datetime_range_picker',
                array('field_options_start' => array('format' => 'yyyy-MM-dd HH:mm:ss'),
                    'field_options_end' => array('format' => 'yyyy-MM-dd HH:mm:ss'))
            )
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('email')
            ->add('username')
            ->add('lastName')
            ->add('firstName')
            ->add('phone')
            ->add('roles', 'choice', array(
                'choices'  => array('ROLE_USER'=>'User', 'ROLE_MODERATOR'=>'Worker', 'ROLE_SUPER_ADMIN'=>'Admin'),
                'multiple' => true
            ))
            ->add('enabled', null, array('editable'=>true))
            ->add('isValid', null, array('editable'=>true, 'label'=>'Valid'))
//            ->add('created')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // get container
        $container = $this->getConfigurationPool()->getContainer();

        $roles = $container->getParameter('security.role_hierarchy.roles');

        $formMapper
            ->tab('General')
            ->with('General', array('class'=> 'col-md-6'))
            ->add('email')
            ->add('username')
            ->add('roles', 'choice', array(
                'choices'  => array('ROLE_USER'=>'User',
                    'ROLE_MODERATOR'=>'Worker',
                    'ROLE_ADMIN'=>'Admin'),
                'multiple' => true
            ))
            ->add('plainPassword', 'repeated', array('first_name' => 'password',
                'required' => false,
                'second_name' => 'confirm',
                'type' => 'password',
                'invalid_message' => 'Passwords do not match',
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password')))
            ->end()
            ->with('Main info', array('class'=> 'col-md-6'))
            ->add('phone')
            ->add('lastName')
            ->add('firstName')
            ->end()
            ->end()
            ->tab('Emails')
            ->with('Emails', array('class' =>'col-sm-12',
                'box-class' => 'box box-solid box-danger',
                'description'=>'User parent emails'))
            ->add('userEmails', 'sonata_type_collection', array(), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'id',
                    'delete'=>true
                )
            )
            ->end()
            ->end()
            /*->tab('Price')
            ->with('Price', array('class' =>'col-sm-12',
                'box-class' => 'box box-solid box-danger',
                'description'=>'User Price'))
            ->add('salePercent', null, ['label'=>'% Sale Percent',
                'help' => 'When user have a global percent for all products'])
            ->add('userPrice', 'sonata_type_collection', array(), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'id',
                    'delete'=>true,
                    'help'=>'when user have a individual percent for current product'
                )
            )
            ->end()
            ->end()*/
        ;
    }

    protected $formOptions = array(
        'cascade_validation' => true
    );

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('email')
            ->add('phone')
            ->add('username')
            ->add('lastName')
            ->add('firstName')
        ;
    }

    public function preUpdate($object)
    {
        parent::preUpdate($object);


        $this->updatePassword($object);

        if($object->getUserEmails()){
            foreach($object->getUserEmails() as $email) {
                $email->setUser($object);
            }
        }

        /*if($object->getUserPrice()){
            foreach($object->getUserPrice() as $price) {
                $price->setUser($object);
            }
        }*/

    }

    public function prePersist($object)
    {
        parent::prePersist($object);

        $this->updatePassword($object);

        if($object->getUserEmails()){
            foreach($object->getUserEmails() as $email) {
                $email->setUser($object);
            }
        }

        /*if($object->getUserPrice()){
            foreach($object->getUserPrice() as $price) {
                $price->setUser($object);
            }
        }*/
    }

    /**
     * @param $object
     */
    private function updatePassword(User $object)
    {
        // get user manager
        $um = $this->getConfigurationPool()->getContainer()->get('fos_user.user_manager');

        // get plain password
        $plainPassword = $object->getPlainPassword();

        if($plainPassword){
            // update user
            $um->updateUser($object, false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array(
            'xls'
        );
    }

    /**
     * @return array
     */
    public function getExportFields()
    {
        return array(
            'id' => 'id',
            'Username' => 'username',
            'Last Name' => 'lastName',
            'First Name' => 'firstName',
        );
    }

    /**
     * @return
     */
    public function getDataSourceIterator()
    {
        $datagrid = $this->getDatagrid();
        $datagrid->buildPager();

        return $this->getModelManager()->getDataSourceIterator($datagrid, $this->getExportFields());
    }
}