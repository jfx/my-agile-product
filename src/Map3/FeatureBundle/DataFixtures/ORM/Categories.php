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
use Map3\FeatureBundle\Entity\Category;

/**
 * Load category data class.
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
class Categories extends AbstractFixture implements OrderedFixtureInterface
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
        $rootArray = array(
            'baselineone-baseline',
            'baselinetwo-baseline',
            'baselinethree-baseline',
            'baselinefour-baseline',
            'baselinefive-baseline',
            'baselinesix-baseline',
            'baselineclosed-baseline',
            'baselineclosed4releaseclosed-baseline',
        );

        $rootList = array();

        foreach ($rootArray as $i => $ref) {
            $bln = $this->getReference($ref);
            $rootList[$i] = new Category();
            $rootList[$i]->setName($bln->getName());
            $rootList[$i]->setBaseline($bln);

            $manager->persist($rootList[$i]);
            $this->addReference('root'.$i.'-category', $rootList[$i]);
        }

        $dataArray = array(
            array(
                'name' => 'Category 1',
                'parent' => 'root0-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 2',
                'parent' => 'root0-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 3',
                'parent' => 'root0-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 11',
                'parent' => 'category1-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 12',
                'parent' => 'category1-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 13',
                'parent' => 'category1-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 21',
                'parent' => 'category2-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 22',
                'parent' => 'category2-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 23',
                'parent' => 'category2-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 31',
                'parent' => 'category3-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 111',
                'parent' => 'category11-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 112',
                'parent' => 'category11-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 113',
                'parent' => 'category11-category',
                'baseline' => 'baselineone-baseline',
            ),
            array(
                'name' => 'Category 1 - baseline 4',
                'parent' => 'root3-category',
                'baseline' => 'baselinefour-baseline',
            ),
            array(
                'name' => 'Category 2 - baseline 4',
                'parent' => 'root3-category',
                'baseline' => 'baselinefour-baseline',
            ),
            array(
                'name' => 'Category 3 - baseline 4',
                'parent' => 'root3-category',
                'baseline' => 'baselinefour-baseline',
            ),
            array(
                'name' => 'Category 1 - baseline closed',
                'parent' => 'root6-category',
                'baseline' => 'baselineclosed-baseline',
            ),
            array(
                'name' => 'Category 2 - baseline closed',
                'parent' => 'root6-category',
                'baseline' => 'baselineclosed-baseline',
            ),
            array(
                'name' => 'Category 3 - baseline closed',
                'parent' => 'root6-category',
                'baseline' => 'baselineclosed-baseline',
            ),
        );
        $objectList = array();

        foreach ($dataArray as $i => $data) {
            $objectList[$i] = new Category();
            $objectList[$i]->setName($data['name']);
            $objectList[$i]->setDetails('Details 4 '.$data['name']);
            $objectList[$i]->setParent(
                $this->getReference($data['parent'])
            );
            $objectList[$i]->setBaseline(
                $this->getReference($data['baseline'])
            );
            $manager->persist($objectList[$i]);

            // In lowercase and no whitespace
            $ref = strtolower(str_replace(' ', '', $data['name'])).'-category';
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
        return 40;
    }
}
