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

namespace Map3\CoreBundle\Extensions;

use DateTime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Twig extension class.
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
class LocaleDateExtension extends Twig_Extension
{
    /**
     * @var ContainerInterface Container
     */
    protected $container;

    /**
     * Constructor.
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
     * @return Twig_SimpleFilter[] An array of filters
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter(
                'localeDate',
                array($this, 'localeDateFilter')
            ),
            new Twig_SimpleFilter(
                'localeDatetime',
                array($this, 'localeDatetimeFilter')
            ),
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
        $formatLocale = $this->getLocaleDateFormat();

        return $dateTime->format($formatLocale);
    }

    /**
     * Convert a datetime object to a locale date display with time.
     *
     * @param DateTime $dateTime The date to convert.
     *
     * @return string
     */
    public function localeDatetimeFilter(DateTime $dateTime)
    {
        $formatLocale = $this->getLocaleDateFormat();

        return $dateTime->format($formatLocale.' H:i');
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'map3_corebundle_localedate';
    }

    /**
     * Get a the format of a locale date.
     *
     * @return string
     */
    private function getLocaleDateFormat()
    {
        $format = $this->container->getParameter('app.formatDate');

        $formatLocale = str_replace(
            ['dd', 'mm', 'yyyy'],
            ['d', 'm', 'Y'],
            $format
        );

        return $formatLocale;
    }
}
