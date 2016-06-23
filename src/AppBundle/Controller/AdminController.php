<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

/**
 * Class AdminController
 *
 * @package AppBundle\Controller
 */
class AdminController extends BaseAdminController
{
    /**
     * @return \FOS\UserBundle\Model\UserInterface
     */
    public function createNewUsersEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    /**
     * @param User $user
     */
    public function prePersistUsersEntity(User $user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    /**
     * @param User $user
     */
    public function preUpdateUserEntity(User $user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }
}
