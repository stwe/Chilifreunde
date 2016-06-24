<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddressType
 *
 * @package AppBundle\Form
 */
class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', null, array(
                'label' => 'Firma'
            ))
            ->add('name', null, array(
                'label' => 'Name'
            ))
            ->add('street', null, array(
                'label' => 'Straße'
            ))
            ->add('postcode', null, array(
                'label' => 'PLZ'
            ))
            ->add('city', null, array(
                'label' => 'Ort'
            ))
            ->add('country', null, array(
                'label' => 'Land'
            ))
            ->add('phoneNumber', null, array(
                'label' => 'Telefon'
            ))
            ->add('homepage', null, array(
                'label' => 'Homepage'
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Address'
        ));
    }
}
