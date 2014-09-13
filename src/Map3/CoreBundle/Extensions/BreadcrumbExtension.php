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

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Twig_Extension;
use Twig_Function_Method;

/**
 * Display breadcrumb for a product or release.
 *
 * @category  MyAgileProduct
 * @package   Core
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class BreadcrumbExtension extends Twig_Extension
{
    /**
     * @var SecurityContextInterface S. Context
     */
    protected $securityContext;

    /**
     * @var Router Router service
     */
    protected $router;

    /**
     * Constructor
     *
     * @param SecurityContextInterface $securityContext The security context.
     * @param Router                   $router          The router service.
     */
    public function __construct(
        SecurityContextInterface $securityContext,
        Router $router
    ) {
        $this->securityContext = $securityContext;
        $this->router      = $router;
    }

    /**
     * Display general breadcrumb.
     *
     * @param array $levels Label of levels.
     *
     * @return string
     */
    public function breadcrumb(array $levels)
    {
        $breadcrumb  = '<ol class="breadcrumb">';

        $lvl_count = 0;

        foreach ($levels as $level) {

            $id = 'br_lvl'.++$lvl_count;

            if (is_array($level)) {
                $breadcrumb .= '  <li><a id="'.$id.'" href="';
                $breadcrumb .= $level[1].'">'.$level[0].'</a></li>';
            } else {
                $breadcrumb .= '  <li id="'.$id.'" class="active">';
                $breadcrumb .= $level.'</li>';
            }

        }
        $breadcrumb .= '</ol>';

        return $breadcrumb;
    }

    /**
     * Display product breadcrumb.
     *
     * @param string $action Label of action displayed.
     *
     * @return string
     */
    public function productBreadcrumb($action)
    {
        $breadcrumb = $this->breadcrumb(
            array(
                $this->getProductNameUrl(),
                $action
            )
        );

        return $breadcrumb;
    }

    /**
     * Display release breadcrumb.
     *
     * @param string $action Label of action displayed.
     *
     * @return string
     */
    public function releaseBreadcrumb($action)
    {
        $breadcrumb = $this->breadcrumb(
            array(
                $this->getProductNameUrl(),
                $this->getReleaseNameUrl(),
                $action
            )
        );

        return $breadcrumb;
    }

    /**
     * Display baseline breadcrumb.
     *
     * @param string $action Label of action displayed.
     *
     * @return string
     */
    public function baselineBreadcrumb($action)
    {
        $breadcrumb = $this->breadcrumb(
            array(
                $this->getProductNameUrl(),
                $this->getReleaseNameUrl(),
                $this->getBaselineNameUrl(),
                $action
            )
        );

        return $breadcrumb;
    }

    /**
     * Get name and Url to view action for current product.
     *
     * @return array
     */
    private function getProductNameUrl()
    {
        $user = $this->securityContext->getToken()->getUser();

        $product = $user->getCurrentProduct();
        $productUrl = $this->router->generate(
            'product_view',
            array('id' => $product->getId())
        );
        $productName = htmlspecialchars($product->getName());

        return array($productName, $productUrl);
    }

    /**
     * Get name and Url to view action for current release.
     *
     * @return array
     */
    private function getReleaseNameUrl()
    {
        $user = $this->securityContext->getToken()->getUser();

        $release = $user->getCurrentRelease();
        $releaseUrl = $this->router->generate(
            'release_view',
            array('id' => $release->getId())
        );
        $releaseName = htmlspecialchars($release->getName());

        return array($releaseName, $releaseUrl);
    }

    /**
     * Get name and Url to view action for current product.
     *
     * @return array
     */
    private function getBaselineNameUrl()
    {
        $user = $this->securityContext->getToken()->getUser();

        $baseline = $user->getCurrentBaseline();
        $baselineUrl = $this->router->generate(
            'baseline_view',
            array('id' => $baseline->getId())
        );
        $baselineName = htmlspecialchars($baseline->getName());

        return array($baselineName, $baselineUrl);
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'breadcrumb' => new Twig_Function_Method(
                $this,
                'breadcrumb',
                array('is_safe' => array('html'))
            ),
            'product_breadcrumb' => new Twig_Function_Method(
                $this,
                'productBreadcrumb',
                array('is_safe' => array('html'))
            ),
            'release_breadcrumb' => new Twig_Function_Method(
                $this,
                'releaseBreadcrumb',
                array('is_safe' => array('html'))
            ),
            'baseline_breadcrumb' => new Twig_Function_Method(
                $this,
                'baselineBreadcrumb',
                array('is_safe' => array('html'))
            )
        );
    }

    /**
     * Returns the name of this extension.
     *
     * @return string
     */
    public function getName()
    {
        return "map3_corebundle_breadcrumb";
    }
}
