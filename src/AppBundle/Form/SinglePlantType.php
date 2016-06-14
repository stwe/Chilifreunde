<?php

namespace AppBundle\Form;

use AppBundle\Entity\LocationRepository;
use AppBundle\Entity\SourceRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SinglePlantType
 *
 * @package AppBundle\Form
 */
class SinglePlantType extends AbstractType
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
            ->add('source', EntityType::class, array(
                    'label' => 'Bezugsquelle',
                    'placeholder' => '-- Bezugsquelle --',
                    'class' => 'AppBundle\Entity\Source',
                    'query_builder' => function(SourceRepository $er) use ($user) {
                        return $er->qbFindAllSourcesByUser($user);
                    }
                )
            )
            ->add('location', EntityType::class, array(
                'label' => 'Standort',
                'placeholder' => '-- Standort --',
                'class' => 'AppBundle\Entity\Location',
                'query_builder' => function(LocationRepository $er) use ($user) {
                    return $er->qbFindAllLocationsByUser($user);
                }
            ))
        ;
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
