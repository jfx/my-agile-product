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

namespace Map3\ScenarioBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Map3\BaselineBundle\Entity\Baseline;

/**
 * Test entity repository class.
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
class TestRepository extends EntityRepository
{
    /**
     * Get children tests by baseline and scenario id.
     *
     * @param Baseline $baseline The baseline
     * @param int      $sid      Scenario id.
     *
     * @return Test[]
     */
    public function findAllByBaselineScenarioId(Baseline $baseline, $sid)
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.baseline', 'b')
            ->innerJoin('t.scenario', 's')
            ->where('b.id = :baselineId')
            ->andWhere('s.id = :scenarioId')
            ->setParameter('baselineId', $baseline->getId())
            ->setParameter('scenarioId', $sid)
            ->orderBy('t.testDatetime', 'ASC');

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * Get last test by scenario.
     *
     * @param Scenario $scenario The scenario
     *
     * @return Test
     */
    public function findLastTestByScenario(Scenario $scenario)
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.scenario', 's')
            ->where('s.id = :scenarioId')
            ->setParameter('scenarioId', $scenario->getId())
            ->orderBy('t.testDatetime', 'DESC')
            ->setMaxResults(1);

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result;
    }
}
