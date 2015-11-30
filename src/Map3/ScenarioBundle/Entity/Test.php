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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Map3\BaselineBundle\Entity\Baseline;
use Map3\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Test entity class.
 *
 * @category  MyAgileProduct
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2015 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 * @ORM\Table(name="map3_test")
 * @ORM\Entity(repositoryClass="Map3\ScenarioBundle\Entity\TestRepository")
 */
class Test
{
    /**
     * @var int Id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var DateTime Test date
     *
     * @ORM\Column(name="testdatetime", type="datetime")
     * @Assert\NotBlank(message="This value is not a valid datetime")
     * @Assert\Date()
     */
    protected $testDatetime;

    /**
     * @var User The tester
     *
     * @ORM\ManyToOne(targetEntity="Map3\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $tester;

    /**
     * @var Result Result
     *
     * @ORM\ManyToOne(targetEntity="Map3\ScenarioBundle\Entity\Result")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $result;

    /**
     * @var array Steps
     *
     * @ORM\Column(name="steps", type="simple_array", nullable=true)
     */
    protected $steps = array();

    /**
     * @var string Comment
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var Baseline Baseline
     *
     * @ORM\ManyToOne(targetEntity="Map3\BaselineBundle\Entity\Baseline")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $baseline;

    /**
     * @var Scenario Scenario
     *
     * @ORM\ManyToOne(targetEntity="Map3\ScenarioBundle\Entity\Scenario")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $scenario;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Get tree node id.
     *
     * @return string
     */
    public function getNodeId()
    {
        return 'T_'.$this->id;
    }

    /**
     * Set datetime of test.
     *
     * @param DateTime $testDatetime Test datetime.
     *
     * @return Test
     */
    public function setTestDatetime($testDatetime)
    {
        $this->testDatetime = $testDatetime;

        return $this;
    }

    /**
     * Get datetime of test.
     *
     * @return DateTime
     */
    public function getTestDatetime()
    {
        return $this->testDatetime;
    }

    /**
     * Set Test.
     *
     * @param User|null $tester The tester or null when not setted.
     *
     * @return Test
     */
    public function setTester($tester)
    {
        $this->tester = $tester;

        return $this;
    }

    /**
     * Get Tester.
     *
     * @return User
     */
    public function getTester()
    {
        return $this->tester;
    }

    /**
     * Set result.
     *
     * @param Result $result The result of the test
     *
     * @return Test
     */
    public function setResult(Result $result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get Result.
     *
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set steps results.
     *
     * @param array $steps Steps results list
     *
     * @return Tests
     */
    public function setStepsResults($steps)
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * Get steps results.
     *
     * @return array
     */
    public function getStepsResults()
    {
        return $this->steps;
    }

    /**
     * Add a new result step at the end of steps list.
     */
    public function addDefaultStepResult()
    {
        $this->steps[] = Result::DEFAULT_RESULT;
    }

    /**
     * Set comment.
     *
     * @param string $comment Comments
     *
     * @return Test
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set baseline.
     *
     * @param Baseline $bln The baseline
     *
     * @return Scenario
     */
    public function setBaseline(Baseline $bln)
    {
        $this->baseline = $bln;

        return $this;
    }

    /**
     * Get Baseline.
     *
     * @return Baseline
     */
    public function getBaseline()
    {
        return $this->baseline;
    }

    /**
     * Set scenario.
     *
     * @param Scenario $scenario The scenario
     *
     * @return Test
     */
    public function setScenario(Scenario $scenario)
    {
        $this->scenario = $scenario;

        return $this;
    }

    /**
     * Get scenario.
     *
     * @return Scenario
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * Fill steps results with "Skipped" result.
     *
     * @param int $stepsCount count steps defined in scenario
     */
    public function fixStepsResultsMissing($stepsCount)
    {
        $stepsResultsCount = count($this->getStepsResults());

        if ($stepsResultsCount < $stepsCount) {
            $setps2Add = $stepsCount - $stepsResultsCount;
            for ($i = 0; $i < $setps2Add; ++$i) {
                $this->addDefaultStepResult();
            }
        }
    }

    /**
     * Get result id by parsing all results steps.
     *
     * @return int Result::SKIPPED|Result::PASSED|Result::FAILED
     */
    public function parseStepsResults()
    {
        $hasSkippedSteps = false;
        $hasPassedSteps = false;
        $hasfailedSetps = false;

        foreach ($this->steps as $resultStep) {
            switch ($resultStep) {
                case Result::FAILED:
                    $hasfailedSetps = true;
                    break;
                case Result::PASSED:
                    $hasPassedSteps = true;
                    break;
                default:
                    $hasSkippedSteps = true;
            }
        }
        if ($hasfailedSetps) {
            return Result::FAILED;
        } elseif ($hasPassedSteps && !$hasSkippedSteps) {
            return Result::PASSED;
        } else {
            return Result::SKIPPED;
        }
    }
}
