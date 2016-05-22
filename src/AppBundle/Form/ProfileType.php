<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProfileType
 *
 * @package AppBundle\Form
 */
class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username');
        /*
        $builder
            ->add('addresses', 'collection', array(
                'type' => new AddressType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'cascade_validation' => true,
                'by_reference' => false
            ))
        ;
        */
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}
