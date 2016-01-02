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

/**
 * Result entity repository class.
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
class ResultRepository extends EntityRepository
{
    /**
     * Get the default result.
     *
     * @return object|null Priority object
     */
    public function findDefaultResult()
    {
        return $this->find(Result::DEFAULT_RESULT);
    }

    /**
     * Get all possible ordered resuts.
     *
     * @return array
     */
    public function getAllOrdered()
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'ASC');
        $resultsLvl2 = $qb->getQuery()->getArrayResult();

        $resultsLvl1 = array();
        foreach ($resultsLvl2 as $result) {
            $resultsLvl1[$result['id']] = $result['label'];
        }

        return $resultsLvl1;
    }
}
