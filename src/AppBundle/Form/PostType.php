<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class PostType
 *
 * @package AppBundle\Form
 */
class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eventStart', null, array(
                'label' => 'Ereignis von',
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control input-inline post-datepicker-start',
                    'data-date-format' => 'dd.mm.yyyy'
                )
            ))
            ->add('eventEnd', null, array(
                'label' => 'Ereignis bis',
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control input-inline post-datepicker-end',
                    'data-date-format' => 'dd.mm.yyyy'
                )
            ))
            ->add('title', null, array(
                'label' => 'Titel'
            ))
            ->add('content', null, array(
                'label' => 'Beitrag'
            ))
            ->add('images', CollectionType::class, array(
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
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
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }
}
