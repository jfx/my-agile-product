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

namespace Map3\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Load user data class.
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
class Users extends AbstractFixture implements
    OrderedFixtureInterface,
    ContainerAwareInterface
{
    /**
     * @var ContainerInterface Container
     */
    private $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface
     *
     *
     * @codeCoverageIgnore
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager The entity manager
     *
     *
     * @codeCoverageIgnore
     */
    public function load(ObjectManager $manager)
    {
        $dataArray = array(
            array(
                'name'       => 'admin',
                'details'    => 'Admin user',
                'superadmin' => true,
                'locked'     => false,
            ),
            array(
                'name'       => 'user',
                'details'    => 'user role on product 1 + user+ on product 2',
                'superadmin' => false,
                'locked'     => false,
            ),
            array(
                'name'       => 'lock',
                'details'    => 'Locked user',
                'superadmin' => false,
                'locked'     => true,
            ),
            array(
                'name'       => 'd1-none',
                'details'    => 'none role on product 1',
                'superadmin' => false,
                'locked'     => false,
            ),
            array(
                'name'       => 'd1-guest',
                'details'    => 'guest role on product 1',
                'superadmin' => false,
                'locked'     => false,
            ),
            array(
                'name'       => 'd1-user+',
                'details'    => 'user+ role on product 1',
                'superadmin' => false,
                'locked'     => false,
            ),
            array(
                'name'       => 'd1-manager',
                'details'    => 'manager role on product 1',
                'superadmin' => false,
                'locked'     => false,
            ),
            array(
                'name'       => 'd2-manager',
                'details'    => 'manager role on product 2',
                'superadmin' => false,
                'locked'     => false,
            ),
            array(
                'name'       => 'no-product',
                'details'    => 'user with no role on product',
                'superadmin' => false,
                'locked'     => false,
            ),
        );

        $userManager = $this->container->get('fos_user.user_manager');
        $objectList = array();

        foreach ($dataArray as $i => $data) {
            // In lowercase and no whitespace
            $name = strtolower(str_replace(' ', '', $data['name']));

            $objectList[$i] = $userManager->createUser();
            $objectList[$i]->setEnabled(true);

            $objectList[$i]->setFirstname('First'.$name);
            $objectList[$i]->setName(ucfirst($name));
            $objectList[$i]->setDisplayname('display'.$name);
            $objectList[$i]->setUsername('user'.$name);
            $objectList[$i]->setPlainPassword($name);
            $objectList[$i]->setEmail($name.'@example.com');
            $objectList[$i]->setSuperAdmin($data['superadmin']);
            $objectList[$i]->setDetails($data['details']);
            $objectList[$i]->setLocked($data['locked']);

            $userManager->updateUser($objectList[$i]);

            $ref = $name.'-user';
            $this->addReference($ref, $objectList[$i]);
        }
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getOrder()
    {
        return 20;
    }
}
