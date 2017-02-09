<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditApplicationUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('file', 'file', array('label' => 'Brochure file', 'required' => true, 'attr'=>array('class'=>'form-control file-left')))
            ->add('save', 'submit', array('label' => 'Upload Pdf', 'attr' => array('class' => 'btn btn-info pill-left')));

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'app_bundle_credit_application_upload_type';
    }
}
