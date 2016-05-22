<?php

namespace AppBundle\Form;

use AppBundle\Form\DataTransformer\SimpleArrayTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class ChiliType
 *
 * @package AppBundle\Form
 */
class ChiliType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'Name'
            ))
            ->add('alternativeNames', TextType::class, array(
                'label' => 'Alternative Namen',
                'required' => false
            ))
            ->add('description', null, array(
                'label' => 'Beschreibung'
            ))
            ->add('heat', null, array(
                'label' => 'Schärfe',
                'placeholder' => '-- auswählen --',
            ))
            ->add('origin', null, array(
                'label' => 'Herkunft'
            ))
            ->add('growth', null, array(
                'label' => 'Wuchs'
            ))
            ->add('fruitcolor', null, array(
                'label' => 'Fruchtfarbe',
                'placeholder' => '-- auswählen --',
            ))
            ->add('maturity', null, array(
                'label' => 'Reifezeit',
                'placeholder' => '-- auswählen --',
            ))
            ->add('species', null, array(
                'label' => 'Art',
                'placeholder' => '-- auswählen --',
            ))
            ->add('usages', null, array(
                'label' => 'Verwendung',
                'expanded' => true,
                'multiple' => true,
                'required' => false
            ))
            ->add('images', CollectionType::class, array(
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                //'constraints' => new Valid()
            ))
        ;

        $builder->get('alternativeNames')
            ->addModelTransformer(new SimpleArrayTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Chili'
        ));
    }
}
