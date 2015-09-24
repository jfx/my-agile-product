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

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Map3\ScenarioBundle\Entity\Test;

/**
 * Load test data class.
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
class Tests extends AbstractFixture implements OrderedFixtureInterface
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
        $dateT1 = new DateTime();
        $dateT1->setDate(date('Y'), date('m') - 1, 10)->setTime(12, 30, 30);
        $dateT2 = new DateTime();
        $dateT2->setDate(date('Y'), date('m') - 1, 11)->setTime(12, 30, 30);
        $dateT3 = new DateTime();
        $dateT3->setDate(date('Y'), date('m') - 1, 12)->setTime(12, 30, 30);

        $dataArray = array(
            array(
                'testDate' => $dateT1,
                'tester' => 'user-user',
                'result' => 'fai-result',
                'steps' => array(2, 2, 3, 1, 1, 1, 1),
                'comment' => 'Test1 S4',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's4f1-passed-steps-scenario',
            ),
            array(
                'testDate' => $dateT2,
                'tester' => 'user-user',
                'result' => 'pas-result',
                'steps' => array(2, 2, 2, 2, 2, 2, 2),
                'comment' => 'Test2 S4',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's4f1-passed-steps-scenario',
            ),
            array(
                'testDate' => $dateT1,
                'result' => 'ski-result',
                'steps' => array(2, 2, 2, 1, 1, 1, 1),
                'comment' => 'Test1 S5',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's5f1-failed-stepscomment-scenario',
            ),
            array(
                'testDate' => $dateT2,
                'tester' => 'd1-user+-user',
                'result' => 'fai-result',
                'steps' => array(2, 2, 3, 1, 1, 1, 1),
                'comment' => 'Test2 S5',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's5f1-failed-stepscomment-scenario',
            ),
            array(
                'testDate' => $dateT1,
                'tester' => 'user-user',
                'result' => 'fai-result',
                'steps' => array(2, 2, 3, 1, 1, 1),
                'comment' => 'Test1 S6',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's6f1-undefined-datatable-scenario',
            ),
            array(
                'testDate' => $dateT2,
                'tester' => 'user-user',
                'result' => 'ski-result',
                'steps' => array(2, 2, 1, 1, 1, 1),
                'comment' => 'Test2 S6',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's6f1-undefined-datatable-scenario',
            ),
            array(
                'testDate' => $dateT1,
                'tester' => 'user-user',
                'result' => 'pas-result',
                'steps' => array(2, 2, 2, 2),
                'comment' => 'Test1 S7',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's7f1-passed-token-scenario',
            ),
            array(
                'testDate' => $dateT1,
                'tester' => 'user-user',
                'result' => 'fai-result',
                'steps' => array(2, 3, 1, 1),
                'comment' => 'Test1 S8',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's8f1-failed-emptyline-scenario',
            ),
            array(
                'testDate' => $dateT1,
                'tester' => 'user-user',
                'result' => 'pas-result',
                'steps' => array(2, 2, 2, 2, 2, 2),
                'comment' => 'Test1 S13 - less',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's13f2-countsteps-scenario',
            ),
            array(
                'testDate' => $dateT2,
                'tester' => 'user-user',
                'result' => 'pas-result',
                'steps' => array(2, 2, 2, 2, 2, 2, 2, 2),
                'comment' => 'Test2 S13 - more',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's13f2-countsteps-scenario',
            ),
            array(
                'testDate' => $dateT1,
                'tester' => 'user-user',
                'result' => 'pas-result',
                'steps' => array(2, 2, 2, 2, 2, 2, 2),
                'comment' => 'Test1 S14',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's14f21-scenario',
            ),
            array(
                'testDate' => $dateT1,
                'tester' => 'user-user',
                'result' => 'ski-result',
                'steps' => array(2, 2, 2, 2, 2, 2, 1),
                'comment' => 'Test1 S15',
                'baseline' => 'baselineone-baseline',
                'scenario' => 's15f0-scenario',
            ),
            array(
                'testDate' => $dateT1,
                'tester' => 'user-user',
                'result' => 'pas-result',
                'steps' => array(2, 2, 2, 2, 2, 2, 2),
                'comment' => 'Test1 S16',
                'baseline' => 'baselineclosed-baseline',
                'scenario' => 's16f0-closed-scenario',
            ),
        );
        $objectList = array();

        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Test();
            $objectList[$i]->setTestDatetime($data['testDate']);
            if (isset($data['tester'])) {
                $objectList[$i]->setTester(
                    $this->getReference($data['tester'])
                );
            }
            $objectList[$i]->setResult(
                $this->getReference($data['result'])
            );
            $objectList[$i]->setStepsResults($data['steps']);
            $objectList[$i]->setComment($data['comment']);
            $objectList[$i]->setBaseline(
                $this->getReference($data['baseline'])
            );
            $objectList[$i]->setScenario(
                $this->getReference($data['scenario'])
            );
            $manager->persist($objectList[$i]);

            // In lowercase and no whitespace
            $rf = strtolower(str_replace(' ', '', $data['comment'])).'-test';
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
        return 70;
    }
}
