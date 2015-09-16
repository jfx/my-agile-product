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
namespace Map3\ScenarioBundle\Extensions;

use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Twig extension class.
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
class StepHtmlFormatExtension extends Twig_Extension
{
    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return Twig_SimpleFilter[] An array of filters
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter(
                'stepHtmlFormat',
                array($this, 'stepHtmlFormatFilter'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * Highlight gherkin keywords or token.
     *
     * @param string $step The step to format.
     *
     * @return string
     */
    public function stepHtmlFormatFilter($step)
    {
        $stepHtmlEntities = htmlentities($step);
        $stepHighlight = preg_replace(
            "/(^Given|^When|^Then|^And|^But|^\*)/",
            '<strong>${1}</strong>',
            $stepHtmlEntities
        );

        return $stepHighlight;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'map3_scenariobundle_stepHtmlformat';
    }
}
