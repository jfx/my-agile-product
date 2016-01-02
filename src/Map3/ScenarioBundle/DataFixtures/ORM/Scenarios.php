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
 * Load scenarios data class.
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
Given some precondition
And some other precondition
When some action by the actor
And some other action
And yet another action
Then some testable outcome is achieved
And something else we can check happens too
EOT;

        $stepsWComment = <<<EOT
Given some precondition
And some other precondition

# Comment 1
When some action by the actor
And some other action
And yet another action
            
Then some testable outcome is achieved
And something else we can check happens too      
EOT;

        $stepsDataTable = <<<EOT
Given there are users:
| username | password   | email             |
| user1    | pass4user1 | user1@example.com |
| user2    | pass4user2 | user2@example.com |
And I am on login page
When I fill in "username" with "user1"
And I fill in "password" with "pass4user1"
And I press "login"
Then I should see "Hello User1 !"
EOT;

        $stepsOutline = <<<EOT
Given there are <start> cucumbers
When I eat <eat> cucumbers
Then I should have <left> cucumbers

Examples:
  | start | eat | left |
  |  12   |  5  |  7   |
  |  20   |  5  |  15  |
EOT;

        $stepsToken = <<<EOT
* Action 1
* Action 2
* Action 3
And action 4
 * Action 5
            
Action 6
EOT;

        $stepsTokenOutline = <<<EOT
* there are <start> cucumbers
* I eat <eat> cucumbers
* I should have <left> cucumbers

Examples:
  | start | eat | left |
  |  12   |  5  |  7   |
  |  20   |  5  |  15  |
EOT;

        $stepsTokenOutlineWithEL = <<<EOT
* there are <start> cucumbers
            
* I eat <eat> cucumbers
            
* I should have <left> cucumbers

Examples:
  | start | eat | left |
  |  12   |  5  |  7   |
  |  20   |  5  |  15  |
EOT;

        $stepsELine = <<<EOT
Action 1
More Action 1
           
Action 2
Continue action 2
           
 * Action 3
    
Then Action 4
EOT;

        $dataArray = array(
            array(
                'title' => 'S 1 F1 - Not implemented',
                'status' => 'not-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 2 F1 - Unchecked',
                'extid' => 'SC-002',
                'status' => 'unc-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 3 F1 - Pending',
                'extid' => 'SC-003',
                'status' => 'pen-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 4 F1 - Passed - Steps',
                'extid' => 'SC-004',
                'status' => 'pas-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 5 F1 - Failed - Steps comment',
                'extid' => 'SC-005',
                'status' => 'fai-status',
                'steps' => $stepsWComment,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 6 F1 - Undefined - Data table',
                'extid' => 'SC-006',
                'status' => 'und-status',
                'steps' => $stepsDataTable,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 7 F1 - Passed - Token',
                'extid' => 'SC-004',
                'status' => 'pas-status',
                'steps' => $stepsToken,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 8 F1 - Failed - Empty line',
                'extid' => 'SC-005',
                'status' => 'fai-status',
                'steps' => $stepsELine,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 9 F1 - Pending - Outline',
                'extid' => 'SC-005',
                'status' => 'pen-status',
                'steps' => $stepsOutline,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 10 F1 - Undefined - Outline',
                'extid' => 'SC-006',
                'status' => 'und-status',
                'steps' => $stepsOutline,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 11 F1 - Passed - Token Outline',
                'extid' => 'SC-004',
                'status' => 'pas-status',
                'steps' => $stepsTokenOutline,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 12 F1 - Failed - Token Outline Empty line',
                'extid' => 'SC-005',
                'status' => 'fai-status',
                'steps' => $stepsTokenOutlineWithEL,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec1-feature',
            ),
            array(
                'title' => 'S 13 F2 - count steps',
                'extid' => 'SC-007',
                'status' => 'pas-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec2-feature',
            ),
            array(
                'title' => 'S 14 F21',
                'extid' => 'SC-008',
                'status' => 'pas-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec21-feature',
            ),
            array(
                'title' => 'S 15 F0',
                'extid' => 'SC-009',
                'status' => 'und-status',
                'steps' => $steps,
                'baseline' => 'baselineone-baseline',
                'feature' => 'featurec0-feature',
            ),
            array(
                'title' => 'S 16 F0 - closed',
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
