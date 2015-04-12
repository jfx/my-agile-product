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

namespace Map3\ReleaseBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Map3\ReleaseBundle\Entity\Release;

/**
 * Load release data class.
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
class Releases extends AbstractFixture implements OrderedFixtureInterface
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
        $dateR1 = new DateTime();
        $dateR1->setDate(date('Y'), date('m')+1, 20)->setTime(12, 0, 0);
        $dateR2 = new DateTime();
        $dateR2->setDate(date('Y'), date('m')+2, 20)->setTime(12, 0, 0);
        $dateR3 = new DateTime();
        $dateR3->setDate(date('Y'), date('m')+3, 20)->setTime(12, 0, 0);
        $dateR4 = new DateTime();
        $dateR4->setDate(date('Y'), date('m')+2, 20)->setTime(12, 0, 0);

        $dataArray = array(
            array(
                'name'        => 'Release One',
                'details'     => 'Details 4 release 1',
                'releaseDate' => $dateR1,
                'closed'      => false,
                'product'     => 'productone-product',
            ),
            array(
                'name'        => 'Release Two',
                'details'     => 'Details 4 release 2',
                'releaseDate' => $dateR2,
                'closed'      => false,
                'product'     => 'productone-product',
            ),
            array(
                'name'        => 'Release Closed',
                'details'     => 'Details 4 release closed',
                'releaseDate' => new DateTime('2014-08-20 12:00:00'),
                'closed'      => true,
                'product'     => 'productone-product',
            ),
            array(
                'name'        => 'Release Three',
                'details'     => 'Details 4 release 3',
                'releaseDate' => $dateR3,
                'closed'      => false,
                'product'     => 'producttwo-product',
            ),
            array(
                'name'        => 'Release Four',
                'details'     => 'Details 4 release 4',
                'releaseDate' => $dateR4,
                'closed'      => false,
                'product'     => 'productthree-product',
            ),            array(
                'name'        => 'Release Five',
                'details'     => 'Details 4 release 5',
                'releaseDate' => $dateR4,
                'closed'      => false,
                'product'     => 'productfour-product',
            ),
        );
        $objectList = array();

        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Release();
            $objectList[$i]->setName($data['name']);
            $objectList[$i]->setDetails($data['details']);
            $objectList[$i]->setReleaseDate($data['releaseDate']);
            $objectList[$i]->setClosed($data['closed']);
            $objectList[$i]->setProduct($this->getReference($data['product']));

            $manager->persist($objectList[$i]);

            // In lowercase and no whitespace
            $ref = strtolower(str_replace(' ', '', $data['name'])).'-release';
            $this->addReference($ref, $objectList[$i]);
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
        return 20;
    }
}
