<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PageController
 *
 * @package AppBundle\Controller
 */
class PageController extends Controller
{
    /**
     * Homepage.
     *
     * @Route("/", name="homepage")
     */
    public function homeAction()
    {
        return $this->render('page/home.html.twig');
    }

    /**
     * Who is online?
     *
     * @Route("/online", name="online")
     * @Security("has_role('ROLE_USER')")
     */
    public function onlineAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->getActive();

        /** @var UserInterface $user */
        /*
        foreach ($users as $user) {
            $roles = $user->getRoles();
            foreach ($roles as $key => $role) {
                if ('ROLE_ADMIN' === $role || 'ROLE_SUPER_ADMIN' === $role) {
                    unset($users[$key]);
                }
            }
        }
        */

        return $this->render(
            'page/online.html.twig',
            array(
                'users' => $users
            )
        );
    }

    /**
     * Show public profile.
     *
     * @param User $user
     *
     * @Route("/member/{id}", name="member")
     * @ParamConverter("user", class="AppBundle:User")
     * @Security("has_role('ROLE_USER')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function memberAction(User $user)
    {
        return $this->render(
            'page/member.html.twig',
            array(
                'user' => $user
            )
        );
    }
}
