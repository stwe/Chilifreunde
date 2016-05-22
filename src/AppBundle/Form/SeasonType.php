<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class SeasonType
 *
 * @package AppBundle\Form
 */
class SeasonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'label' => 'Titel'
            ))
            ->add('description', null, array(
                'label' => 'Beschreibung',
            ))
            ->add('start', null, array(
                'label' => 'Start',
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control input-inline season-datepicker-start',
                    'data-date-format' => 'dd.mm.yyyy'
                )
            ))
            ->add('end', null, array(
                'label' => 'Ende',
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control input-inline season-datepicker-end',
                    'data-date-format' => 'dd.mm.yyyy'
                )
            ))
            ->add('plants', CollectionType::class, array(
                'entry_type' => PlantType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'constraints' => new Valid()
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Season'
        ));
    }
}
