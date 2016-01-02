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

namespace Map3\ScenarioBundle\Service;

use Doctrine\ORM\EntityManager;
use Map3\ScenarioBundle\Entity\Result;
use Map3\ScenarioBundle\Entity\Scenario;
use Map3\ScenarioBundle\Entity\Status;

/**
 * Scenario service class.
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
class ScenarioService
{
    /**
     * @var EntityManager Entity manager
     */
    protected $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager The entity manager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Update scenario status.
     *
     * @param Scenario $scenario The scenario to update
     */
    public function updateStatus(Scenario $scenario)
    {
        $status = $scenario->getStatus();
        $statusId = $status->getId();

        // Status not changed if initial status is Not implemented or unchecked
        if (($statusId != Status::NOT_IMPL_STATUS)
            || ($statusId != Status::UNCHECKED_STATUS)
        ) {
            $testRepository = $this->entityManager
                ->getRepository('Map3ScenarioBundle:Test');
            $lastTest = $testRepository->findLastTestByScenario($scenario);

            //If no test, scenario status = Pending
            if (is_null($lastTest)) {
                $newStatusId = Status::PENDING_STATUS;
            } else {
                $resultTestId = $lastTest->getResult()->getId();

                switch ($resultTestId) {
                    case Result::SKIPPED:
                        $newStatusId = Status::UNDEF_STATUS;
                        break;
                    case Result::PASSED:
                        $newStatusId = Status::PASSED_STATUS;
                        break;
                    case Result::FAILED:
                        $newStatusId = Status::FAILED_STATUS;
                        break;
                }
            }
            $statusRepository = $this->entityManager
                ->getRepository('Map3ScenarioBundle:Status');
            $newStatus = $statusRepository->find($newStatusId);
            $scenario->setStatus($newStatus);
        }
    }
}
