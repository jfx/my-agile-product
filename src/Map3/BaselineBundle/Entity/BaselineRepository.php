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
 * Baseline entity repository class.
 *
 * @category  MyAgileProduct
 * @package   Baseline
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class BaselineRepository extends EntityRepository
{
    /**
     * Get all baselines for a release.
     *
     * @param Release $rls The release.
     *
     * @return array List of baselines.
     */
//    public function findBaselinesByRelease(Release $rls)
    public function findBaselinesByRelease($rls)
    {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.release', 'r')
            ->where('r.id = :releaseId')
            ->setParameter('releaseId', $rls->getId())
            ->orderBy('b.baselineDatetime', 'ASC');

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * Count all baselines for a release.
     *
     * @param Release $rls The release.
     *
     * @return int count of releases.
     */
    public function countBaselinesByRelease(Release $rls)
    {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.release', 'r')
            ->select('count(b.id)')
            ->where('r.id = :releaseId')
            ->setParameter('releaseId', $rls->getId());

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }
}
