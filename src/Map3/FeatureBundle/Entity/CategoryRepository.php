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

namespace Map3\FeatureBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Map3\BaselineBundle\Entity\Baseline;

/**
 * Category entity repository class.
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
class CategoryRepository extends NestedTreeRepository
{
    /**
     * Get root category by baseline.
     *
     * @param Baseline $baseline The baseline
     *
     * @return Category[]
     */
    public function findRootByBaseline(Baseline $baseline)
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.baseline', 'b')
            ->where('b.id = :baselineId')
            ->andWhere('c.parent is NULL')
            ->setParameter('baselineId', $baseline->getId());

        $result = $qb->getQuery()->getSingleResult();

        return $result;
    }

    /**
     * Get children categories by baseline and parent id.
     *
     * @param Baseline $baseline The baseline
     * @param int      $pid      Parent id.
     *
     * @return Category[]
     */
    public function findChildrenByBaselineParentId(Baseline $baseline, $pid)
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.baseline', 'b')
            ->innerJoin('c.parent', 'p')
            ->where('b.id = :baselineId')
            ->andWhere('p.id = :parentId')
            ->setParameter('baselineId', $baseline->getId())
            ->setParameter('parentId', $pid)
            ->orderBy('c.lft', 'ASC');

        $results = $qb->getQuery()->getResult();

        return $results;
    }
}
