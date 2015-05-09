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

namespace Map3\BaselineBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Reference entity repository class.
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
class ReferenceRepository extends EntityRepository
{
    /**
     * Get all references for a baseline ordered by label.
     *
     * @param Baseline $bln The baseline.
     *
     * @return array List of references.
     */
    public function findReferencesByBaseline($bln)
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.baseline', 'b')
            ->where('b.id = :baselineId')
            ->setParameter('baselineId', $bln->getId())
            ->orderBy('r.label', 'ASC');

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * Count all references for a baseline.
     *
     * @param Baseline $bln The baseline.
     *
     * @return int count of references.
     */
    public function countReferencesByBaseline($bln)
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.baseline', 'b')
            ->select('count(r.id)')
            ->where('b.id = :baselineId')
            ->setParameter('baselineId', $bln->getId());

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }
}
