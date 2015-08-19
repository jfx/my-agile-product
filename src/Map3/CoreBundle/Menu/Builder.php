<?php

/**
 * LICENSE : This file is part of My Agile Product.
 *
 * My Agile Product is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * My Agile Product is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Map3\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Map3\UserBundle\Entity\Role;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Menu builder class.
 *
 * @category  MyAgileProduct
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class Builder extends ContainerAware
{
    /**
     * Create main menu.
     *
     * @param FactoryInterface $factory Factory interface.
     * @param array            $options Options.
     *
     * @return ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array('navbar' => true));

        $menu->addChild(
            'Dashboard',
            array(
                'icon' => 'dashboard',
                'route' => 'map3_dashboard_index',
            )
        );

        $dropdownAdmin = $menu->addChild(
            'Admin',
            array(
                'icon' => 'cog',
                'dropdown' => true,
                'caret' => true,
            )
        );

        $dropdownAdmin->addChild(
            'Profile',
            array(
                'icon' => 'user',
                'route' => 'user_profile',
            )
        );

        $dropdownAdmin->addChild('divider_1', array('divider' => true));

        $dropdownAdmin->addChild(
            'Users',
            array(
                'icon' => 'globe',
                'route' => 'user_index',
            )
        );
        $dropdownAdmin->addChild(
            'Products',
            array(
                'icon' => 'phone',
                'route' => 'product_index',
            )
        );

        return $menu;
    }

    /**
     * Create right menu.
     *
     * @param FactoryInterface $factory Factory interface.
     * @param array            $options Options.
     *
     * @return ItemInterface
     */
    public function rightMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem(
            'root',
            array(
                'navbar' => true,
                'pull-right' => true,
            )
        );

        $token = $this->container->get('security.token_storage')->getToken();
        $user = $token->getUser();
        $username = ucfirst($user->getUsername());
        $roleLabel = $user->getCurrentRoleLabel();

        if (($roleLabel !== null)
            && (strlen($roleLabel) > 0)
            && ($roleLabel != Role::LABEL_NONE)
        ) {
            $roleLabel2Display = '('.$roleLabel.')';
        } else {
            $roleLabel2Display = '';
        }
        if ($user->isSuperAdmin()) {
            $star = '*';
        } else {
            $star = '';
        }
        $menu->addChild(
            $username.$star.' '.$roleLabel2Display,
            array(
                'icon' => 'user',
                'route' => 'user_profile',
            )
        );
        $menu->addChild(
            'Log out',
            array(
                'icon' => 'log-out',
                'route' => 'fos_user_security_logout',
            )
        );

        return $menu;
    }
}
