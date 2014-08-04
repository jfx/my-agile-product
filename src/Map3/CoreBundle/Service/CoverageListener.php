<?php
/**
 * LICENSE : This file is part of My Agile Project.
 *
 * My Agile Project is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * My Agile Project is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Map\CoreBundle\Service;

use AppKernel;
use PHP_CodeCoverage;
use PHP_CodeCoverage_Filter;
use PHP_CodeCoverage_Report_PHP;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Code coverage listener class.
 *
 * @category  MyAgileProject
 * @package   Core
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproject.org
 * @since     2
 */
class CoverageListener
{
    /**
     * @var PHP_CodeCoverage CodeCoverage object
     */
    protected $coverage;

    /**
     * @var boolean is code coverage enabled
     */
    protected $isEnabled = false;

    /**
     * @var boolean is code coverage started
     */
    protected $isStarted = false;

    /**
     * @var string Directory to save *.cov files
     */
    protected $coverageDir;

    /**
     * @static string Name of test
     */
    protected static $coverageDirName = 'coverage';

    /**
     * @var string Name of test
     */
    protected $testName = 'Coverage tests';

    /**
     * @static string Name of test
     */
    protected static $testNameFile = 'testName.txt';

    /**
     * Constructor
     *
     * @param AppKernel $kernel            The Kernel is the heart of Symfony.
     * @param boolean   $isCoverageEnabled Code coverage enabled in conf file.
     *
     * @codeCoverageIgnore
     */
    public function __construct(AppKernel $kernel, $isCoverageEnabled)
    {
        $env = $kernel->getEnvironment();

        if (($env == 'prod') || (!$isCoverageEnabled)) {
            return;
        }
        $this->isEnabled = true;
        $rootDir = $kernel->getRootDir();
        $this->coverageDir  = $kernel->getLogDir().'/'.self::$coverageDirName;

        $filter = new PHP_CodeCoverage_Filter();
        $filter->addDirectoryToWhitelist($rootDir.'/../src');

        $this->coverage = new PHP_CodeCoverage(null, $filter);

        $testNameFilePath = $kernel->getLogDir().'/'.self::$testNameFile;

        if (file_exists($testNameFilePath)) {

            $content = htmlspecialchars(file_get_contents($testNameFilePath));
            $this->testName = substr($content, 0, 100);
        }
    }

    /**
     * At the beginning, starts code coverage.
     *
     * @param GetResponseEvent $event The event object.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$this->isEnabled) {
            return;
        }
        $this->coverage->start($this->testName);
        $this->isStarted = true;
    }

    /**
     * At the end, stops code coverage and save data in file *.cov.
     *
     * @param FilterResponseEvent $event The event object.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $reqType = $event->getRequestType();

        if ((!$this->isEnabled)
            || (HttpKernelInterface::MASTER_REQUEST !== $reqType)
            || (!$this->isStarted)
        ) {
            return;
        }
        $this->coverage->stop();

        $writer = new PHP_CodeCoverage_Report_PHP();
        $writer->process(
            $this->coverage,
            $this->coverageDir.'/'.microtime(true).'.cov'
        );
    }
}
