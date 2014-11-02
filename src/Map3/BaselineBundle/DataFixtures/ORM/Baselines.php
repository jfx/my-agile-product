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

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Map3\BaselineBundle\Entity\Baseline;

/**
 * Load baseline data class.
 *
 * @category  MyAgileProduct
 * @package   Baseline
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class Baselines extends AbstractFixture implements OrderedFixtureInterface
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
        $dateTimeB1 = new DateTime("+ 10 days");
        $dateTimeB1->setTime(12, 00, 00);
        $dateTimeB2 = new DateTime("+ 20 days");
        $dateTimeB2->setTime(12, 00, 00);
        $dateTimeB3 = new DateTime("+ 1 months");
        $dateTimeB3->setTime(12, 00, 00);
        $dateTimeB4 = new DateTime("+ 2 months");
        $dateTimeB4->setTime(12, 00, 00);
        
        $dataArray = array(
            array(
                'name'         => 'Baseline One',
                'details'      => 'Details 4 baseline 1',
                'baselineDate' => $dateTimeB1,
                'closed'       => false,
                'release'      => 'releaseone-release',
            ),
            array(
                'name'         => 'Baseline Two',
                'details'      => 'Details 4 baseline 2',
                'baselineDate' => $dateTimeB2,
                'closed'       => false,
                'release'      => 'releaseone-release',
            ),
            array(
                'name'         => 'Baseline Closed',
                'details'      => 'Details 4 baseline closed',
                'baselineDate' => new DateTime("2014-08-02 12:00:00"),
                'closed'       => true,
                'release'      => 'releaseone-release',
            ),
            array(
                'name'         => 'Baseline Three',
                'details'      => 'Details 4 baseline 3',
                'baselineDate' => $dateTimeB3,
                'closed'       => false,
                'release'      => 'releasetwo-release',
            ),
            array(
                'name'         => 'Baseline Four',
                'details'      => 'Details 4 baseline 4',
                'baselineDate' => $dateTimeB4,
                'closed'       => false,
                'release'      => 'releasethree-release',
            ),
            array(
                'name'         => 'Baseline Five',
                'details'      => 'Details 4 baseline 5',
                'baselineDate' => new DateTime("+ 1 months"),
                'closed'       => false,
                'release'      => 'releasefour-release',
            ),
        );
        $objectList = array();

        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Baseline();
            $objectList[$i]->setName($data['name']);
            $objectList[$i]->setDetails($data['details']);
            $objectList[$i]->setBaselineDatetime($data['baselineDate']);
            $objectList[$i]->setClosed($data['closed']);
            $objectList[$i]->setRelease($this->getReference($data['release']));

            $manager->persist($objectList[$i]);

            // In lowercase and no whitespace
            $ref = strtolower(str_replace(' ', '', $data['name'])).'-baseline';
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
        return 30;
    }
}
