<?php

namespace AppBundle\Listener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AuthenticationListener
 *
 * @package AppBundle\Listener
 */
class AuthenticationListener implements EventSubscriberInterface
{
    private $loginManager;
    private $firewallName;

    /**
     * AuthenticationListener constructor.
     *
     * @param LoginManagerInterface $loginManager
     * @param                       $firewallName
     */
    public function __construct(LoginManagerInterface $loginManager, $firewallName)
    {
        $this->loginManager = $loginManager;
        $this->firewallName = $firewallName;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'authenticate',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'authenticate',
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'authenticate',
        );
    }

    /**
     * @param FilterUserResponseEvent       $event
     * @param null                          $eventName
     * @param EventDispatcherInterface|null $eventDispatcher
     */
    public function authenticate(FilterUserResponseEvent $event, $eventName = null, EventDispatcherInterface $eventDispatcher = null)
    {
        if (!$event->getUser()->isEnabled()) {
            return;
        }
    }
}
