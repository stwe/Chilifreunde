<?php

namespace AppBundle\Form;

use AppBundle\Entity\Chili;
use AppBundle\Entity\Species;
use AppBundle\Entity\ChiliRepository;
use AppBundle\Entity\SourceRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PlantType
 *
 * @package AppBundle\Form
 */
class PlantType extends AbstractType
{
    /**
     * The doctrine orm entity manager service.
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * Ctor.
     *
     * @param EntityManagerInterface $em
     * @param TokenStorage           $tokenStorage
     *
     * @throws \Exception
     */
    public function __construct(EntityManagerInterface $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;

        $token = $tokenStorage->getToken();

        if (null !== $token) {
            $user = $token->getUser();

            if (is_object($user) && $user instanceof UserInterface) {
                $this->user = $user;
            } else {
                throw new \Exception();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->user;

        $builder
            ->add('sowing')
            //->add('quantity')
            ->add('source', EntityType::class, array(
                    'placeholder' => '-- Bezugsquelle --',
                    'class' => 'AppBundle\Entity\Source',
                    'query_builder' => function(SourceRepository $er) use ($user) {
                        return $er->qbFindAllSourcesByUser($user);
                    }
                )
            )
            //->add('note')
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    /**
     * Add elements.
     *
     * @param FormInterface $form
     * @param Species|null  $species
     * @param bool          $public
     */
    private function addElements(FormInterface $form, Species $species = null, $public)
    {
        $user = $this->user;

        // Default values for new action

        // Public = false
        $form->add('public', CheckboxType::class, array(
            'data' => $public,
            'label' => false,
            'required' => false,
            'mapped' => false
        ));

        // Get all Species
        $form->add('species', EntityType::class, array(
                'data' => $species,
                'placeholder' => '-- Art --',
                'class' => 'AppBundle\Entity\Species',
                'mapped' => false
            )
        );

        // Get chilis by species
        $form->add('chili', EntityType::class, array(
                'placeholder' => '-- Sorte --',
                'class' => 'AppBundle\Entity\Chili',
                'query_builder' => function(ChiliRepository $er) use ($public, $species, $user) {
                    return $er->qbFindAllChilisBySpeciesAndPublic($public, $species, $user);
                }
            )
        );

        // ------------------------------

        // Edit action

        $chilis = null;
        if (null !== $species) {
            $publicFormData = $form->get('public')->getData();
            $repo = $this->em->getRepository('AppBundle:Chili');
            $chilis = $repo->findAllChilisBySpeciesAndPublic($publicFormData, $species, $user);
            $form->add('chili', EntityType::class, array(
                    'placeholder' => '-- Sorte --',
                    'class' => 'AppBundle\Entity\Chili',
                    'choices' => $chilis
                )
            );
        }
    }

    /**
     * Handle PRE_SUBMIT event.
     *
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $species = null;
        $public = array_key_exists('public', $data) ? true : false;

        if ('' != $data['species']) {
            $species = $this->em->getRepository('AppBundle:Species')->find($data['species']);
        } else {
            $chili = $this->em->getRepository('AppBundle:Chili')->find($data['chili']);
            $species = $chili->getSpecies();
        }

        $this->addElements($form, $species, $public);
    }

    /**
     * Handle PRE_SET_DATA event.
     *
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $plant = $event->getData();

        // defaults for new action
        $species = null;
        $public = false;

        // get data for edit action
        if (null !== $plant) {
            if ($plant->getChili()) {
                $species = $plant->getChili()->getSpecies();
                $public = $plant->getChili()->isPublic();
            }
        }

        $this->addElements($form, $species, $public);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Plant'
        ));
    }
}
