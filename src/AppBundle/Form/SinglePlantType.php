<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SinglePlantType
 *
 * @package AppBundle\Form
 */
class SinglePlantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('chili', null, array(
                'label' => 'Chilisorte'
            ))
            ->add('sowing', null, array(
                'label' => 'Wieviel ausgesät'
            ))
            ->add('quantity', null, array(
                'label' => 'Anzahl Pflanzen'
            ))
            ->add('note', null, array(
                'label' => 'Notiz'
            ))
            ->add('source', null, array(
                'label' => 'Bezugsquelle'
            ))
            ->add('location', null, array(
                'label' => 'Standort'
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Plant'
        ));
    }
}
