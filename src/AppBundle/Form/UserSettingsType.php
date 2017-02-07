<?php

namespace AppBundle\Form;

use AppBundle\Entity\UserSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tradeName', 'text', array('label'=>'business / trade name', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('tradeAddress', 'text', array('label'=>'business / trade address', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('ownershipType', 'choice', array(
                'choices'=>array(
                    UserSettings::PROPRIETORSHIP=>'Proprietorship',
                    UserSettings::PARTNERSHIP=>'Partnership',
                    UserSettings::CORPORATION=>'Corporation',
                    UserSettings::LIMITED_LIABILITY_COMPANY=>'Limited Liability Company',
                    UserSettings::STATE_OF_INCORPORATION=>'State of Incorporation'
                )
            ))

            ->add('corporateName', 'text',  array('label'=>'legal corporate name or business', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true)
                )
            ->add('taxId'
                , 'text',  array('label'=>'federal tax i.d.', 'label_attr'=>
                    array('class'=>'text-capitalize'), 'required'=>false)
            )
           /* ->add('stateId', 'text',  array('label'=>'state i.d', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))*/
            /*->add('hin', 'text',  array('label'=>'hin', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))*/
            ->add('statePharmacyLicense', 'text',  array('label'=>'State Pharmacy License / Distributor License', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('deaLicense', 'text',  array('label'=>'dea license', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('stateControlledSubstanceLicense', 'text',  array('label'=>'state controlled substance license', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))

            ->add('shippingAddressState', 'text',  array('label'=>'state', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('shippingAddressCity', 'text',  array('label'=>'city', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
//            ->add('shippingAddressCounty', 'text',  array('label'=>'county', 'label_attr'=>
//                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('shippingAddressStreet', 'text',  array('label'=>'street', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
//            ->add('shippingAddressNumber', 'text',  array('label'=>'number', 'label_attr'=>
//                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('shippingAddressZip', 'text',  array('label'=>'zip', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))


            ->add('primeryBusinessContact', 'text',  array('label'=>'business contact', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('primeryBusinessContactTitle', 'text',  array('label'=>'business title', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('primeryBusinessContactEmail', 'text',  array('label'=>'business email', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('primeryPurchasingContact', 'text',  array('label'=>'purchasing contact', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('primeryPurchasingContactTitle', 'text',  array('label'=>'purchasing title', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('primeryPurchasingContactEmail', 'text',  array('label'=>'purchasing emil', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))

            ->add('businessTelephone','text',  array('label'=>'business telephone', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('businessFax','text',  array('label'=>'business fax', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))

            ->add('supplierWholesalers', 'text',  array('label'=>'supplier wholesalers', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('supplierWholesalersAddress', 'text',  array('label'=>'supplier wholesalers address', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('supplierWholesalersPhone', 'text',  array('label'=>'supplier wholesalers phone', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('supplierWholesalersContact', 'text',  array('label'=>'supplier wholesalers contact', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))

             ->add('supplierWholesalers2', 'text',  array('label'=>'supplier wholesalers 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('supplierWholesalersAddress2', 'text',  array('label'=>'supplier wholesalers address 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('supplierWholesalersPhone2', 'text',  array('label'=>'supplier wholesalers phone 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('supplierWholesalersContact2', 'text',  array('label'=>'supplier wholesalers contact 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))

             ->add('supplierWholesalers3', 'text',  array('label'=>'supplier wholesalers 3', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('supplierWholesalersAddress3', 'text',  array('label'=>'supplier wholesalers address 3', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('supplierWholesalersPhone3', 'text',  array('label'=>'supplier wholesalers phone 3', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('supplierWholesalersContact3', 'text',  array('label'=>'supplier wholesalers contact 3', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))


            ->add('bankName', 'text',  array('label'=>'bank name', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('bankCity', 'text',  array('label'=>'bank city', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('bankState', 'text',  array('label'=>'bank state', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
           /* ->add('bankAccountOfficer', 'text',  array('label'=>'account officer', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))*/
            ->add('bankPhone', 'text',  array('label'=>'bank phone', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('bankAccount', 'text',  array('label'=>'bank account', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))

             ->add('bankName2', 'text',  array('label'=>'bank name 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('bankCity2', 'text',  array('label'=>'bank city 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('bankState2', 'text',  array('label'=>'bank state 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
          /*  ->add('bankAccountOfficer2', 'text',  array('label'=>'account officer 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))*/
            ->add('bankPhone2', 'text',  array('label'=>'bank phone 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))
            ->add('bankAccount2', 'text',  array('label'=>'bank account 2', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>false))



            ->add('businessType', 'choice',
                array('choices'=>
                    array('retail_pharmacy'=>'Retail Pharmacy',
                        'hospital_pharmacy'=>'Hospital Pharmacy',
                        'nursing_home'=>'Nursing Home',
                        'long-term_care_facility'=>'Long-term Care Facility',
                        'outpatient_clinic'=>'Outpatient Clinic',
                        'distributor'=>'Distributor',
                        '340b_clinic'=>'340b Clinic',
                        're-packager'=>'Re-packager',
                    ), 'multiple'=>true, 'label'=>'Select Business Type',
                    'label_attr'=>array('class'=>'text-capitalize')
                ))

            ->add('businessYear', 'number',  array('label'=>'business year', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('businessMonthlyVolume', 'number',  array('label'=>'business Monthly Volume', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('businessMonthlyGenericSales', 'number',  array('label'=>'business Monthly Generic Sales ($)', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('businessMonthlyBrandSales', 'number',  array('label'=>'business Monthly Brand Sales ($)', 'label_attr'=>
                array('class'=>'text-capitalize'), 'required'=>true))
            ->add('save', 'submit', array('label' => 'Save', 'attr' => array('class'=>'btn btn-success btn-square')))
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UserSettings'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_usersettings';
    }


}
