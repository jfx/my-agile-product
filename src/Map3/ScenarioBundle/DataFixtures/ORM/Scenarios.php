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

namespace Map3\ScenarioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Map3\ScenarioBundle\Entity\Scenario;

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
class Scenarios extends AbstractFixture implements OrderedFixtureInterface
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
        $steps = <<<EOT
Given step-given 1
And step-given-and 2
When step-when 3
And step-when-and 4
Then step-then 5
And step-then-and 6
EOT;
        
        $dataArray = array(
            array(
                'title' => 'Scenario 1 F1',
                'status' => 'not-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'Scenario 2 F1',
                'extid' => 'SC-002',
                'status' => 'unc-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'Scenario 3 F1',
                'extid' => 'SC-003',
                'status' => 'pen-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'Scenario 4 F1',
                'extid' => 'SC-004',
                'status' => 'pas-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'Scenario 5 F1',
                'extid' => 'SC-005',
                'status' => 'fai-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'Scenario 6 F1',
                'extid' => 'SC-006',
                'status' => 'und-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'Scenario 7 F2',
                'extid' => 'SC-007',
                'status' => 'fai-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec2-feature',
            ),
            array(
                'title' => 'Scenario 8 F21',
                'extid' => 'SC-008',
                'status' => 'pas-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec21-feature',
            ),
            array(
                'title' => 'Scenario 9 F0',
                'extid' => 'SC-009',
                'status' => 'und-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec0-feature',
            ),
            array(
                'title' => 'Scenario 10 F0 - closed',
                'extid' => 'SC-010',
                'status' => 'pas-status',
                'steps' => $steps,
                'baseline' => 'baselineclosed-baseline',
                'feature' => 'feature1-baselineclosed-feature',
            ),
        );
        $objectList = array();

        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Scenario();
            $objectList[$i]->setTitle($data['title']);
            if (isset($data['extid'])) {
                $objectList[$i]->setExtId($data['extid']);
            }
            $objectList[$i]->setStatus(
                $this->getReference($data['status'])
            );
            $objectList[$i]->setSteps($data['steps']);
            $objectList[$i]->setBaseline(
                $this->getReference($data['baseline'])
            );
            $objectList[$i]->setFeature(
                $this->getReference($data['feature'])
            );
            $manager->persist($objectList[$i]);

            // In lowercase and no whitespace
            $rf = strtolower(str_replace(' ', '', $data['title'])).'-scenario';
            $this->addReference($rf, $objectList[$i]);
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
        return 60;
    }
}
