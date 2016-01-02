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

namespace Map3\FeatureBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Map3\FeatureBundle\Entity\Feature;

/**
 * Load feature data class.
 *
 * @category  MyAgileProduct
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2015 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class Features extends AbstractFixture implements OrderedFixtureInterface
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
                'title' => 'Feature C1',
                'extid' => 'ID-001',
                'priority' => 'p0-priority',
                'description' => 'Narrative F1',
                'baseline' => 'baselineone-baseline',
                'category' => 'category1-category',
            ),
            array(
                'title' => 'Feature C2',
                'extid' => 'ID-002',
                'priority' => 'p1-priority',
                'description' => 'Narrative F2',
                'baseline' => 'baselineone-baseline',
                'category' => 'category1-category',
            ),
            array(
                'title' => 'Feature C3',
                'priority' => 'p2-priority',
                'description' => 'Narrative F3',
                'baseline' => 'baselineone-baseline',
                'category' => 'category1-category',
            ),
            array(
                'title' => 'Feature C4 P3',
                'priority' => 'p3-priority',
                'description' => 'Narrative F4',
                'baseline' => 'baselineone-baseline',
                'category' => 'category1-category',
            ),
            array(
                'title' => 'Feature C21',
                'priority' => 'p3-priority',
                'description' => 'Narrative F4+',
                'baseline' => 'baselineone-baseline',
                'category' => 'category21-category',
            ),
            array(
                'title' => 'Feature C0',
                'priority' => 'p0-priority',
                'description' => 'Narrative F5',
                'baseline' => 'baselineone-baseline',
                'category' => 'root0-category',
            ),
            array(
                'title' => 'Feature 1 - baseline closed',
                'extid' => 'ID-001',
                'priority' => 'p0-priority',
                'description' => 'Narrative F1 - baseline closed',
                'baseline' => 'baselineclosed-baseline',
                'category' => 'root6-category',
            ),
        );
        $objectList = array();

        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Feature();
            $objectList[$i]->setTitle($data['title']);
            if (isset($data['extid'])) {
                $objectList[$i]->setExtId($data['extid']);
            }
            $objectList[$i]->setPriority(
                $this->getReference($data['priority'])
            );
            $objectList[$i]->setDescription($data['description']);
            $objectList[$i]->setBaseline(
                $this->getReference($data['baseline'])
            );
            $objectList[$i]->setCategory(
                $this->getReference($data['category'])
            );
            $manager->persist($objectList[$i]);

            // In lowercase and no whitespace
            $feat = strtolower(str_replace(' ', '', $data['title'])).'-feature';
            $this->addReference($feat, $objectList[$i]);
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
        return 50;
    }
}
