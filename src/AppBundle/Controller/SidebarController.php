<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SidebarController
 *
 * @package AppBundle\Controller
 */
class SidebarController extends Controller
{
    /**
     * Get latest Seasons.
     *
     * @Template(":sidebar:seasons.html.twig")
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function latestSeasonsAction()
    {
        $seasons = $this->getDoctrine()->getManager()->getRepository('AppBundle:Season')->getLatestSeasons(5);

        return array(
            'seasons' => $seasons,
        );
    }

    /**
     * Get latest posts.
     *
     * @Template(":sidebar:posts.html.twig")
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function latestPostsAction()
    {
        $posts = $this->getDoctrine()->getManager()->getRepository('AppBundle:Post')->getLatestPosts(5);

        return array(
            'posts' => $posts,
        );
    }

    /**
     * Who is online?
     *
     * @Template(":sidebar:online.html.twig")
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function whoIsOnlineAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->getActive();

        /** @var UserInterface $user */
        /*
        foreach ($users as $user) {
            $roles = $user->getRoles();
            foreach ($roles as $key => $role) {
                if ("ROLE_ADMIN" === $role || "ROLE_SUPER_ADMIN" === $role) {
                    unset($users[$key]);
                }
            }
        }
        */

        return array(
            'users' => $users,
        );
    }
}
