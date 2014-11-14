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

namespace Map3\ProductBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Controller\CoreController;
use Map3\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

/**
 * Product controller class.
 *
 * @category  MyAgileProduct
 * @package   Product
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class ReleaseController extends CoreController
{
    /**
     * List of releases
     *
     * @param Product $product The product
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction(Product $product)
    {
        $this->setCurrentProduct($product, array('ROLE_DM_GUEST'));

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3ReleaseBundle:Release');

        $releases = $repository->findReleasesByProduct($product);

        return $this->render(
            'Map3ProductBundle:Release:index.html.twig',
            array(
                'releases' => $releases,
                'product'  => $product,
            )
        );
    }
}
