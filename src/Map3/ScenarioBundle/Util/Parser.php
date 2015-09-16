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
namespace Map3\ScenarioBundle\Util;

//use Doctrine\ORM\EntityManager;

/**
 * Scenario steps parser class.
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
class Parser
{
    const GHERKIN = 1;
    const TOKEN = 2;
    const EMPTY_LINE = 3;

    const GHERKIN_PATTERN = '/(^Given|^When|^Then|^And|^But)/';
    const TOKEN_PATTERN = "/(^\*)/";
    const OUTLINE_PATTERN = "/(^\||Examples)/";
    const EXAMPLES_PATTERN = 'Examples';

    /**
     * @var string Steps
     */
    protected $stepsRaw;

    /**
     * @var array steps splitted by line
     */
    protected $stepsSplitByLine;

    /**
     * @var array steps trimmed without comment, empty line at begining/end
     */
    protected $stepsTrimmed;

    /**
     * @var array start steps index
     */
    protected $startStepsIndex;

    /**
     * @var array Steps without examples
     */
    protected $stepsWithoutExamples;

    /**
     * @var array parsed steps in array
     */
    protected $stepsArray;

    /**
     * @var int type of scenario description
     */
    protected $type;

    /**
     * @var bool Is outline scenario
     */
    protected $outline = false;

    /**
     * @var array Examples array
     */
    protected $examplesArray;

    /**
     * Constructor.
     *
     * @param string $steps Steps of scenario
     */
    public function __construct($steps)
    {
        $this->stepsRaw = $steps;
    }

    /**
     * Parse steps.
     */
    public function parse()
    {
        $this->stepsSplitByLine = $this->splitByLine($this->stepsRaw);

        $this->stepsTrimmed = $this->removeUnnecessaryLines(
            $this->stepsSplitByLine
        );

        $this->stepsWithoutExamples = $this->analyseSetOutline(
            $this->stepsTrimmed
        );

        $this->startStepsIndex = $this->analyseSetType(
            $this->stepsWithoutExamples
        );

        $this->stepsArray = $this->splitSteps(
            $this->startStepsIndex,
            $this->stepsWithoutExamples
        );
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Is outline scenario.
     *
     * @return bool
     */
    public function isOutline()
    {
        return $this->outline;
    }

    /**
     * Get lines count without empty lines at begining and end.
     *
     * @return int Lines count
     */
    public function getLinesCount()
    {
        return count($this->stepsTrimmed);
    }

    /**
     * Get steps count.
     *
     * @return int Steps count
     */
    public function getStepsCount()
    {
        return count($this->startStepsIndex);
    }

    /**
     * Get steps in html format in array.
     *
     * @return array
     */
    public function getFormatedSteps()
    {
        return $this->stepsArray;
    }

    /**
     * Get examples in array.
     *
     * @return array
     */
    public function getExamples()
    {
        return $this->examplesArray;
    }

    /**
     * Split by line and trime each line.
     *
     * @param string $input String to parse
     *
     * @return array Array splitted by line
     */
    protected function splitByLine($input)
    {
        return array_map('trim', preg_split("/\r\n|\n|\r/", $input));
    }

    /**
     * Remove empty and comment lines.
     *
     * @param array $input Array splitted by line
     *
     * @return array Array trimmed
     */
    protected function removeUnnecessaryLines(array $input)
    {
        $array = $input;
        // Remove comment line
        foreach ($array as $key => $line) {
            if (mb_strpos($line, '#') === 0) {
                unset($array[$key]);
            }
        }
        //Remove empty end line at beginning
        $arrayBEL = array_values($array);
        $countBEL = count($arrayBEL);
        $iBEL = 0;
        while (($iBEL < $countBEL) && (strlen($arrayBEL[$iBEL]) == 0)) {
            unset($arrayBEL[$iBEL]);
            ++$iBEL;
        }
        $arrayWEEL = $this->removeEmptyEndLine($arrayBEL);

        // Remove duplicate empty line
        $prevCountLine = 1;
        $endIdx = count($arrayWEEL) - 1;
        for ($i = $endIdx; $i >= 0; --$i) {
            $countLine = strlen($arrayWEEL[$i]);
            if (($prevCountLine == 0) && ($countLine == 0)) {
                $prevI = $i + 1;
                unset($arrayWEEL[$prevI]);
            }
            $prevCountLine = $countLine;
        }

        return array_values($arrayWEEL);
    }

    /**
     * Analyse steps to define type of scenario.
     *
     * @param array $input Array trimmed
     *
     * @return array Array Steps index
     */
    protected function analyseSetOutline(array $input)
    {
        $count = count($input);
        $idx = $count - 1;
        $examplesCount = 0;

        while (($idx >= 0)
            && (preg_match(self::OUTLINE_PATTERN, $input[$idx]) === 1)
        ) {
            if (strpos($input[$idx], self::EXAMPLES_PATTERN) !== false) {
                ++$examplesCount;
            }
            --$idx;
        }
        $outlineIdx = $idx + 1;
        $outlineLength = $count - $outlineIdx;

        // IF outline : 3 lines, start with Examples and only one "Examples"
        if ($outlineLength > 2
            && (strpos($input[$outlineIdx], self::EXAMPLES_PATTERN) !== false)
            && ($examplesCount == 1)
        ) {
            $this->outline = true;
            $this->examplesArray = array_slice($input, ($outlineIdx + 1));

            $output = $this->removeEmptyEndLine(
                array_slice($input, 0, $outlineIdx)
            );
        } else {
            $this->examplesArray = array();
            $output = $input;
        }

        return $output;
    }
    /**
     * Analyse steps to define type of scenario.
     *
     * @param array $input Array trimmed
     *
     * @return array Array Steps index
     */
    protected function analyseSetType(array $input)
    {
        $ELineEndStepsArray = array();
        $gherkinStartStepsArr = array();
        $tokenStartStepsArray = array();

        foreach ($input as $index => $line) {
            if (strlen($line) == 0) {
                $ELineEndStepsArray[] = $index;
            } elseif (preg_match(self::GHERKIN_PATTERN, $line)) {
                $gherkinStartStepsArr[] = $index;
            } elseif (preg_match(self::TOKEN_PATTERN, $line)) {
                $tokenStartStepsArray[] = $index;
            }
        }
        $countGherkin = count($gherkinStartStepsArr);
        $countToken = count($tokenStartStepsArray);
        $countELine = count($ELineEndStepsArray);
        if (($countGherkin > 1)
            && ($countGherkin >= $countToken)
            && ($countGherkin >= $countELine)) {
            $this->type = self::GHERKIN;

            return $gherkinStartStepsArr;
        } elseif (($countToken > 1) && ($countToken >= $countELine)) {
            $this->type = self::TOKEN;

            return $tokenStartStepsArray;
        } elseif (count($input) > 0) {
            $ELineStartStepsArray = array(0);
            foreach ($ELineEndStepsArray as $end) {
                $ELineStartStepsArray[] = ++$end;
            }
            $this->type = self::EMPTY_LINE;

            return $ELineStartStepsArray;
        } else {
            $this->type = self::EMPTY_LINE;

            return array();
        }
    }

    /**
     * Split steps in array.
     *
     * @param array $stepsIndex          Steps index
     * @param array $stepsWithoutOutline Only steps in array
     *
     * @return array Array Formated steps
     */
    protected function splitSteps(array $stepsIndex, array $stepsWithoutOutline)
    {
        $steps = array();
        $countStepsWO = count($stepsWithoutOutline);

        for ($idx = 0; $idx < count($stepsIndex); ++$idx) {
            $startStepIdx = $stepsIndex[$idx];
            if ($idx == (count($stepsIndex) - 1)) {
                $endStepIdx = $countStepsWO;
            } else {
                $idxNext = $idx + 1;
                $endStepIdx = $stepsIndex[$idxNext];
            }
            $step = '';
            for ($i = $startStepIdx; $i < $endStepIdx; ++$i) {
                if (strlen($stepsWithoutOutline[$i]) > 0) {
                    if ($i == $startStepIdx) {
                        $step .= $stepsWithoutOutline[$i];
                    } else {
                        $step .= PHP_EOL.$stepsWithoutOutline[$i];
                    }
                }
            }
            $steps[] = $step;
        }

        return $steps;
    }

    /**
     * Remove empty end line.
     *
     * @param array $array Array
     *
     * @return array
     */
    protected function removeEmptyEndLine(array $array)
    {
        $arrayEEL = array_values($array);
        $count = count($arrayEEL);
        $i = $count - 1;

        while (($i >= 0) && (strlen($arrayEEL[$i]) == 0)) {
            unset($arrayEEL[$i]);
            --$i;
        }

        return $arrayEEL;
    }
}
