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
use Map3\UserBundle\Entity\UserPdtRole;

/**
 * Load user product role data class.
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
class UserPdtRoles extends AbstractFixture implements OrderedFixtureInterface
{
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
                'user'    => 'user-user',
                'product' => 'productone-product',
                'role'    => 'user-role',
            ),
            array(
                'user'    => 'user-user',
                'product' => 'producttwo-product',
                'role'    => 'user+-role',
            ),
            array(
                'user'    => 'd1-none-user',
                'product' => 'productone-product',
                'role'    => 'none-role',
            ),
            array(
                'user'    => 'd1-guest-user',
                'product' => 'productone-product',
                'role'    => 'guest-role',
            ),
            array(
                'user'    => 'd1-user+-user',
                'product' => 'productone-product',
                'role'    => 'user+-role',
            ),
            array(
                'user'    => 'd1-manager-user',
                'product' => 'productone-product',
                'role'    => 'manager-role',
            ),
            array(
                'user'    => 'd1-manager-user',
                'product' => 'productfour-product',
                'role'    => 'manager-role',
            ),
            array(
                'user'    => 'd2-manager-user',
                'product' => 'producttwo-product',
                'role'    => 'manager-role',
            ),
        );
        $objectList = array();

        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new UserPdtRole();
            $objectList[$i]->setUser($this->getReference($data['user']));
            $objectList[$i]->setProduct($this->getReference($data['product']));
            $objectList[$i]->setRole($this->getReference($data['role']));

            $manager->persist($objectList[$i]);
        }
        $manager->flush();
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
        return 30;
    }
}
