<?php

namespace AppBundle\Listener;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Doctrine\ORM\EntityManager;

/**
 * Class ActivityListener
 *
 * @package AppBundle\Listener
 */
class ActivityListener
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ActivityListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManager         $entityManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManager $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * Update the user "lastActivity" on each request.
     *
     * @param FilterControllerEvent $event
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }

        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();

            if (($user instanceof UserInterface) && !($user->isActiveNow())) {
                $user->setLastActivityAt(new \DateTime());
                $this->entityManager->flush($user);
            }
        }
    }}
