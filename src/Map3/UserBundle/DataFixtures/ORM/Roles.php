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
use Map3\UserBundle\Entity\Role;

/**
 * Load role data class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class Roles extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager The entity manager
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function load(ObjectManager $manager)
    {
        $dataArray = array(
            array('id' => 'ROLE_DM_NONE', 'label' => 'None'),
            array('id' => 'ROLE_DM_GUEST', 'label' => 'Guest'),
            array('id' => 'ROLE_DM_USER', 'label' => 'User'),
            array('id' => 'ROLE_DM_USERPLUS', 'label' => 'User+'),
            array('id' => 'ROLE_DM_MANAGER', 'label' => 'Manager'),
        );
        $objectList = array();

        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Role();
            $objectList[$i]->setId($data['id']);
            $objectList[$i]->setLabel($data['label']);

            $manager->persist($objectList[$i]);

            // In lowercase and no whitespace
            $ref = strtolower(str_replace(' ', '', $data['label'])).'-role';
            $this->addReference($ref, $objectList[$i]);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     *
     * @codeCoverageIgnore
     */
    public function getOrder()
    {
        return 10;
    }
}
