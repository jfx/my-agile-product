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

namespace Map3\BaselineBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Map3\BaselineBundle\Entity\Reference;

/**
 * Load reference data class.
 *
 * @category  MyAgileProduct
 * @package   Baseline
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class References extends AbstractFixture implements OrderedFixtureInterface
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
            array(
                'label'    => 'Reference One',
                'value'    => 'Value 4 reference one',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'label'    => 'Reference Two',
                'value'    => 'Value 4 reference two',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'label'    => 'Reference Three',
                'value'    => 'Value 4 reference three',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'label'    => 'Reference Four',
                'value'    => 'Value 4 reference four',
                'baseline' => 'baselinethree-baseline',
            ),
        );

        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Reference();
            $objectList[$i]->setLabel($data['label']);
            $objectList[$i]->setValue($data['value']);
            $objectList[$i]->setBaseline(
                $this->getReference($data['baseline'])
            );

            $manager->persist($objectList[$i]);
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
        return 40;
    }
}
