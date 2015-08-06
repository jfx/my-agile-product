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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Display breadcrumb for a product or release.
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
class BreadcrumbExtension extends Twig_Extension
{
    /**
     * @var TokenStorageInterface Token storage interface
     */
    protected $tokenStorage;

    /**
     * @var Router Router service
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param TokenStorageInterface $tokenStorage The token storage
     * @param Router                $router       The router service
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        Router $router
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     * Display general breadcrumb.
     *
     * @param array $levels Label of levels
     *
     * @return string
     */
    public function breadcrumb(array $levels)
    {
        $breadcrumb = '<ol class="breadcrumb">';

        $lvlCount = 0;

        foreach ($levels as $level) {
            $id = 'br_lvl'.++$lvlCount;

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
     * @param string $action Label of action displayed
     *
     * @return string
     */
    public function productBreadcrumb($action)
    {
        $breadcrumb = $this->breadcrumb(
            array(
                $this->getProductNameUrl(),
                $action,
            )
        );

        return $breadcrumb;
    }

    /**
     * Display release breadcrumb.
     *
     * @param string $action Label of action displayed
     *
     * @return string
     */
    public function releaseBreadcrumb($action)
    {
        $breadcrumb = $this->breadcrumb(
            array(
                $this->getProductNameUrl(),
                $this->getReleaseNameUrl(),
                $action,
            )
        );

        return $breadcrumb;
    }

    /**
     * Display baseline breadcrumb.
     *
     * @param string $action Label of action displayed
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
                $action,
            )
        );

        return $breadcrumb;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return Twig_SimpleFunction[] An array of functions
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'breadcrumb',
                array(
                    $this,
                    'breadcrumb',
                ),
                array('is_safe' => array('html'))
            ),
            new Twig_SimpleFunction(
                'product_breadcrumb',
                array(
                    $this,
                    'productBreadcrumb',
                ),
                array('is_safe' => array('html'))
            ),
            new Twig_SimpleFunction(
                'release_breadcrumb',
                array(
                    $this,
                    'releaseBreadcrumb',
                ),
                array('is_safe' => array('html'))
            ),
            new Twig_SimpleFunction(
                'baseline_breadcrumb',
                array(
                    $this,
                    'baselineBreadcrumb',
                ),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * Returns the name of this extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'map3_corebundle_breadcrumb';
    }

    /**
     * Get name and Url to view action for current product.
     *
     * @return string[]
     */
    private function getProductNameUrl()
    {
        $user = $this->tokenStorage->getToken()->getUser();

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
     * @return string[]
     */
    private function getReleaseNameUrl()
    {
        $user = $this->tokenStorage->getToken()->getUser();

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
     * @return string[]
     */
    private function getBaselineNameUrl()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $baseline = $user->getCurrentBaseline();
        $baselineUrl = $this->router->generate(
            'baseline_view',
            array('id' => $baseline->getId())
        );
        $baselineName = htmlspecialchars($baseline->getName());

        return array($baselineName, $baselineUrl);
    }
}
