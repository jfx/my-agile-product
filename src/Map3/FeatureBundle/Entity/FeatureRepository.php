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

use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Map3\BaselineBundle\Entity\Baseline;

/**
 * Feature entity repository class.
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
class FeatureRepository extends SortableRepository
{
    /**
     * Get children features by baseline and category id.
     *
     * @param Baseline $baseline The baseline
     * @param int      $cid      Category id.
     *
     * @return Feature[]
     */
    public function findFeatureByBaselineCategoryId(Baseline $baseline, $cid)
    {
        $qb = $this->createQueryBuilder('f')
            ->innerJoin('f.baseline', 'b')
            ->innerJoin('f.category', 'c')
            ->where('b.id = :baselineId')
            ->andWhere('c.id = :categoryId')
            ->setParameter('baselineId', $baseline->getId())
            ->setParameter('categoryId', $cid)
            ->orderBy('f.position', 'ASC');

        $results = $qb->getQuery()->getResult();

        return $results;
    }
}
