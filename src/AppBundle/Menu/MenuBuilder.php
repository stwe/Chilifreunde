<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class MenuBuilder
 *
 * @package AppBundle\Menu
 */
class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Main menu.
     *
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Home');

        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Chili-Datenbanken')
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'glyphicon glyphicon-fire');
        $menu['Chili-Datenbanken']->addChild('Meine Chili-Datenbank', array('route' => 'chili_private'))
            ->setAttribute('icon', 'glyphicon glyphicon-fire');
        $menu['Chili-Datenbanken']->addChild('Öffentliche Chili-Datenbank', array('route' => 'chili_public'))
            ->setAttribute('icon', 'glyphicon glyphicon-fire');

        $menu->addChild('Saisonverwaltung')
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'glyphicon glyphicon-grain');
        $menu['Saisonverwaltung']->addChild('Meine Anbaulisten', array('route' => 'season_private'))
            ->setAttribute('icon', 'glyphicon glyphicon-grain');
        $menu['Saisonverwaltung']->addChild('Alle Anbaulisten', array('route' => 'season_public'))
            ->setAttribute('icon', 'glyphicon glyphicon-grain');

        $menu->addChild('Stammdaten')
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'glyphicon glyphicon-cog');

        /*
        $menu['Stammdaten']->addChild('Meine Standorte', array('route' => 'address_new'))
            ->setAttribute('icon', 'glyphicon glyphicon-home');
        */

        $menu['Stammdaten']->addChild('Meine Bezugsquellen', array('route' => 'source_private'))
            ->setAttribute('icon', 'glyphicon glyphicon-shopping-cart');
        $menu['Stammdaten']->addChild('Öffentliche Bezugsquellen', array('route' => 'source_public'))
            ->setAttribute('icon', 'glyphicon glyphicon-shopping-cart');

        return $menu;
    }

    /**
     * User menu.
     *
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Home');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Backend', array('route' => 'admin'))
                ->setAttribute('icon', 'glyphicon glyphicon-dashboard');
        }

        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $username = $this->container->get('security.token_storage')->getToken()->getUser()->getUsername();
            $menu->addChild('User', array('label' => $username))
                ->setAttribute('dropdown', true)
                ->setAttribute('icon', 'glyphicon glyphicon-user');
            $menu['User']->addChild('Mein Profil', array('route' => 'fos_user_profile_show'))
                ->setAttribute('icon', 'fa fa-gear');
            $menu['User']->addChild('Passwort ändern', array('route' => 'fos_user_change_password'))
                ->setAttribute('icon', 'fa fa-lock');
            $menu['User']->addChild('Profil bearbeiten', array('route' => 'fos_user_profile_edit'))
                ->setAttribute('icon', 'fa fa-edit')
                ->setAttribute('divider_append', true);
            $menu['User']->addChild('Logout', array('route' => 'fos_user_security_logout'))
                ->setAttribute('icon', 'glyphicon glyphicon-log-out');
        } else {
            $menu->addChild('Registrieren', array('route' => 'fos_user_registration_register'))
                ->setAttribute('icon', 'fa fa-user-plus');
            $menu->addChild('Login', array('route' => 'fos_user_security_login'))
                ->setAttribute('icon', 'glyphicon glyphicon-log-in');
        }

        return $menu;
    }
}
