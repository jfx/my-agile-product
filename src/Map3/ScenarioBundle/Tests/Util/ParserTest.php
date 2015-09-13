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
namespace Map3\ScenarioBundle\Tests\Util;

use Map3\ScenarioBundle\Util\Parser;

/**
 * Test steps parser class.
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
class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $defaultSteps = <<<EOT
Given some precondition
And some other precondition

# Comment 1
When some action by the actor
And some other action
And yet another action
            
Then some testable outcome is achieved
And something else we can check happens too      
EOT;

    private $stepsDataTable = <<<EOT
Given there are users:
| username | password   | email             |
| user1    | pass4user1 | user1@example.com |
| user2    | pass4user2 | user2@example.com |
And I am on login page
When I fill in "username" with "user1"
And I fill in "password" with "pass4user1"
And I press "login"
Then I should see "Hello User1 !"
EOT;
   
    private $stepsOutline = <<<EOT
Given there are <start> cucumbers
When I eat <eat> cucumbers
Then I should have <left> cucumbers

Examples:
  | start | eat | left |
  |  12   |  5  |  7   |
  |  20   |  5  |  15  |
EOT;

    private $stepsToken = <<<EOT
* Action 1
* Action 2
* Action 3
And action 4
 * Action 5
            
Action 6
EOT;

    private $stepsTokenOutline = <<<EOT
* there are <start> cucumbers
* I eat <eat> cucumbers
* I should have <left> cucumbers

Examples:
  | start | eat | left |
  |  12   |  5  |  7   |
  |  20   |  5  |  15  |
EOT;

    private $stepsTokenOutlineWithEL = <<<EOT
* there are <start> cucumbers
            
* I eat <eat> cucumbers
            
* I should have <left> cucumbers

Examples:
  | start | eat | left |
  |  12   |  5  |  7   |
  |  20   |  5  |  15  |
EOT;
    
    private $stepsELine = <<<EOT
Action 1
More Action 1
           
Action 2
Continue action 2
           
 * Action 3
    
Then Action 4
EOT;
    
    private $noSteps = '';
    
    private $stepsOneLine = 'Step in one line';

    /**
     * Get test conditions.
     * 
     * @retun array
     */
    protected function getCondition()
    {
        $condition = array();
        $condition['Gherkin'] = array(
            $this->defaultSteps,
            Parser::GHERKIN,
            7,
            false 
        );
        $condition['GherkinDataTable'] = array(
            $this->stepsDataTable,
            Parser::GHERKIN,
            6,
            false
        );
        $condition['GherkinOutline'] = array(
            $this->stepsOutline,
            Parser::GHERKIN,
            3,
            true
        );
        $condition['Token'] = array(
            $this->stepsToken,
            Parser::TOKEN,
            4,
            false
        );
        $condition['TokenOutline'] = array(
            $this->stepsTokenOutline,
            Parser::TOKEN,
            3,
            true
        );
        $condition['TokenOutlineWithEmptyLine'] = array(
            $this->stepsTokenOutlineWithEL,
            Parser::TOKEN,
            3,
            true
        );
        $condition['EmptyLine'] = array(
            $this->stepsELine,
            Parser::EMPTY_LINE,
            4,
            false
        );
        $condition['NoStep'] = array(
            $this->noSteps,
            Parser::EMPTY_LINE,
            0,
            false
        );
        $condition['OneLine'] = array(
            $this->stepsOneLine,
            Parser::EMPTY_LINE,
            1,
            false
        );
        return $condition;
    }
    /**
     * Test method.
     */
    public function testGetLinesCount()
    {
        $parser = new Parser($this->defaultSteps);
        $parser->parse();
        
        $this->assertEquals(9, $parser->getLinesCount());
    }

    /**
     * Test method.
     */
    public function testGetLinesCountWithComment()
    {
        $steps = ' # XXXX '.PHP_EOL;
        $steps .= '# Comment 2'.PHP_EOL;
        $steps .= $this->defaultSteps;
        $steps .= '### Comment 3'.PHP_EOL;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(9, $parser->getLinesCount());
    }
    
     /**
     * Test method.
     */
    public function testGetLinesCountWithEmptyorBlankLineAtBeginning()
    {
        $steps = '    '.PHP_EOL;
        $steps .= ' '.PHP_EOL;
        $steps .= PHP_EOL;
        $steps .= $this->defaultSteps;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(9, $parser->getLinesCount());
    }
    
    /**
     * Test method.
     */
    public function testGetLinesCountWithEmptyorBlankLineAtEnd()
    {
        $steps = $this->defaultSteps.PHP_EOL;
        $steps .= '    '.PHP_EOL;
        $steps .= ' '.PHP_EOL;
        $steps .= PHP_EOL;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(9, $parser->getLinesCount());
    }
    
    /**
     * Test method.
     */
    public function testGetLinesCountWithMultipleEmptyLine()
    {
        $steps = $this->stepsELine.PHP_EOL;
        $steps .= '    '.PHP_EOL;
        $steps .= ' '.PHP_EOL;
        $steps .= PHP_EOL;
        $steps .= 'Step added 1'.PHP_EOL;
        $steps .= '    '.PHP_EOL;
        $steps .= ' '.PHP_EOL;
        $steps .= PHP_EOL;
        $steps .= 'Step added 2'.PHP_EOL;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(13, $parser->getLinesCount());
    }
    
    /**
     * Test method.
     */
    public function testGetLinesCountWithNoSteps()
    {
        $steps = $this->noSteps;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(0, $parser->getLinesCount());
    }

    /**
     * Test method.
     */
    public function testGetType()
    {
        $condition = $this->getCondition();
        foreach ($condition as $key => $value) {
            $parser = new Parser($value[0]);
            $parser->parse();
        
            $this->assertEquals($value[1], $parser->getType(), $key);
        }
    }
    
    /**
     * Test method.
     */
    public function testGetStepsCount()
    {
        $condition = $this->getCondition();
        foreach ($condition as $key => $value) {
            $parser = new Parser($value[0]);
            $parser->parse();
        
            $this->assertEquals($value[2], $parser->getStepsCount(), $key);
        }
    }

    /**
     * Test method.
     */
    public function testIsOutline()
    {
        $condition = $this->getCondition();
        foreach ($condition as $key => $value) {
            $parser = new Parser($value[0]);
            $parser->parse();
        
            $this->assertEquals($value[3], $parser->isOutline(), $key);
        }
    }
    
    /**
     * Test method.
     */
    public function testGetFormatedStepsGherkin()
    {
        $steps = $this->defaultSteps;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arraySteps = array(
            '<strong>Given</strong> some precondition',
            '<strong>And</strong> some other precondition',
            '<strong>When</strong> some action by the actor',
            '<strong>And</strong> some other action',
            '<strong>And</strong> yet another action',
            '<strong>Then</strong> some testable outcome is achieved',
            '<strong>And</strong> something else we can check happens too', 
        );

        $this->assertEquals($arraySteps, $parser->getFormatedSteps());
    }
    
    /**
     * Test method.
     */
    public function testGetFormatedStepsDataTable()
    {
        $steps = $this->stepsDataTable;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arraySteps = array(    
            '<strong>Given</strong> there are users:<br/>| username | password   | email             |<br/>| user1    | pass4user1 | user1@example.com |<br/>| user2    | pass4user2 | user2@example.com |',
            '<strong>And</strong> I am on login page',
            '<strong>When</strong> I fill in &quot;username&quot; with &quot;user1&quot;',
            '<strong>And</strong> I fill in &quot;password&quot; with &quot;pass4user1&quot;',
            '<strong>And</strong> I press &quot;login&quot;',
            '<strong>Then</strong> I should see &quot;Hello User1 !&quot;'
        );

        $this->assertEquals($arraySteps, $parser->getFormatedSteps());
    }
    
    /**
     * Test method.
     */
    public function testGetFormatedStepsOutline()
    {
        $steps = $this->stepsOutline;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arraySteps = array(    
            '<strong>Given</strong> there are &lt;start&gt; cucumbers',
            '<strong>When</strong> I eat &lt;eat&gt; cucumbers',
            '<strong>Then</strong> I should have &lt;left&gt; cucumbers'
        );
        $this->assertEquals($arraySteps, $parser->getFormatedSteps()); 
    }
    
    /**
     * Test method.
     */
    public function testGetFormatedStepsToken()
    {
        $steps = $this->stepsToken;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arraySteps = array(    
            '<strong>*</strong> Action 1',
            '<strong>*</strong> Action 2',
            '<strong>*</strong> Action 3<br/>And action 4',
            '<strong>*</strong> Action 5<br/>Action 6'
        );

        $this->assertEquals($arraySteps, $parser->getFormatedSteps());
    }
    
    /**
     * Test method.
     */
    public function testGetFormatedStepsTokenOutline()
    {
        $steps = $this->stepsTokenOutline;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arraySteps = array(    
            '<strong>*</strong> there are &lt;start&gt; cucumbers',
            '<strong>*</strong> I eat &lt;eat&gt; cucumbers',
            '<strong>*</strong> I should have &lt;left&gt; cucumbers'
        );

        $this->assertEquals($arraySteps, $parser->getFormatedSteps());
    } 

    /**
     * Test method.
     */
    public function testGetFormatedStepsTokenOutlineWithEL()
    {
        $steps = $this->stepsTokenOutlineWithEL;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arraySteps = array(    
            '<strong>*</strong> there are &lt;start&gt; cucumbers',
            '<strong>*</strong> I eat &lt;eat&gt; cucumbers',
            '<strong>*</strong> I should have &lt;left&gt; cucumbers'
        );

        $this->assertEquals($arraySteps, $parser->getFormatedSteps());
    }

    /**
     * Test method.
     */
    public function testGetFormatedStepsELine()
    {
        $steps = $this->stepsELine;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arraySteps = array(
            'Action 1<br/>More Action 1',
            'Action 2<br/>Continue action 2',
            '* Action 3',
            'Then Action 4'
        );

        $this->assertEquals($arraySteps, $parser->getFormatedSteps());
    }
    
    /**
     * Test method.
     */
    public function testGetFormatedStepsNoLine()
    {
        $steps = $this->noSteps;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arraySteps = array();

        $this->assertEquals($arraySteps, $parser->getFormatedSteps());
    }
    
    /**
     * Test method.
     */
    public function testGetFormatedStepsOneLine()
    {
        $steps = $this->stepsOneLine;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arraySteps = array('Step in one line');

        $this->assertEquals($arraySteps, $parser->getFormatedSteps());
    }
    
    /**
     * Test method.
     */
    public function testGetExamplesGherkin()
    {
        $steps = $this->defaultSteps;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(array(), $parser->getExamples());    
    }

    /**
     * Test method.
     */
    public function testGetExamplesDataTable()
    {
        $steps = $this->stepsDataTable;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(array(), $parser->getExamples());    
    }
    
    /**
     * Test method.
     */
    public function testGetExamplesOutline()
    {
        $steps = $this->stepsOutline;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arrayExamples = array(
            '| start | eat | left |',
            '|  12   |  5  |  7   |',
            '|  20   |  5  |  15  |'
        );
        $this->assertEquals($arrayExamples, $parser->getExamples());    
    }

    /**
     * Test method.
     */
    public function testGetExamplesToken()
    {
        $steps = $this->stepsToken;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(array(), $parser->getExamples());    
    }
    
    /**
     * Test method.
     */
    public function testGetExamplesTokenOutline()
    {
        $steps = $this->stepsTokenOutline;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arrayExamples = array(
            '| start | eat | left |',
            '|  12   |  5  |  7   |',
            '|  20   |  5  |  15  |'
        );
        $this->assertEquals($arrayExamples, $parser->getExamples());    
    }
    
    /**
     * Test method.
     */
    public function testGetExamplesTokenOutlineWithEL()
    {
        $steps = $this->stepsTokenOutlineWithEL;
        $parser = new Parser($steps);
        $parser->parse();
        
        $arrayExamples = array(
            '| start | eat | left |',
            '|  12   |  5  |  7   |',
            '|  20   |  5  |  15  |'
        );
        $this->assertEquals($arrayExamples, $parser->getExamples());    
    }
    
    /**
     * Test method.
     */
    public function testGetExamplesErrorNotEnoughtLines()
    {
        $steps = $this->defaultSteps;
        $steps .= ' '.PHP_EOL;
        $steps .= 'Examples'.PHP_EOL;
        $steps .= '| start | eat | left |'.PHP_EOL;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(false, $parser->isOutline());
        $this->assertEquals(array(), $parser->getExamples());    
    }
    
    /**
     * Test method.
     */
    public function testGetExamplesErrorNotStartingByExamples()
    {
        $steps = $this->defaultSteps;
        $steps .= ' '.PHP_EOL;
        $steps .= '| start | eat | left |'.PHP_EOL;
        $steps .= 'Examples'.PHP_EOL;
        $steps .= '|  12   |  5  |  7   |'.PHP_EOL;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(false, $parser->isOutline());
        $this->assertEquals(array(), $parser->getExamples());    
    }
    
    /**
     * Test method.
     */
    public function testGetExamplesErrorDuplicateExamples()
    {
        $steps = $this->defaultSteps;
        $steps .= ' '.PHP_EOL;
        $steps .= 'Examples'.PHP_EOL;
        $steps .= '| start | eat | left |'.PHP_EOL;
        $steps .= 'Examples'.PHP_EOL;
        $steps .= '|  12   |  5  |  7   |'.PHP_EOL;
        $parser = new Parser($steps);
        $parser->parse();
        
        $this->assertEquals(false, $parser->isOutline());
        $this->assertEquals(array(), $parser->getExamples());    
    }
}
