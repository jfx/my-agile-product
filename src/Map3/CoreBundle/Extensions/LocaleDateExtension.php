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

namespace Map3\CoreBundle\Extensions;

use DateTime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_Filter_Method;

/**
 * Twig extension class.
 *
 * @category  MyAgileProduct
 * @package   Core
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproject.org
 * @since     3
 */
class LocaleDateExtension extends Twig_Extension
{
    /**
     * @var ContainerInterface Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $containerInterface The container.
     */
    public function __construct(ContainerInterface $containerInterface)
    {
        $this->container = $containerInterface;
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'localeDate' => new Twig_Filter_Method($this, 'localeDateFilter')
        );
    }

    /**
     * Convert a datetime object to a locale date display.
     *
     * @param DateTime $dateTime The date to convert.
     *
     * @return string
     */
    public function localeDateFilter(DateTime $dateTime)
    {
        $format = $this->container->getParameter('app.formatDate');

        $formatLocale = str_replace(
            ['dd', 'mm', 'yyyy'],
            ['d', 'm', 'Y'],
            $format
        );

        return $dateTime->format($formatLocale);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'map_corebundle_localedate';
    }
}
