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

namespace Map3\CoreBundle\Controller;

use Map3\CoreBundle\Form\FormHandler;
use Map3\ProductBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Core controller class.
 *
 * @category  MyAgileProduct
 * @package   Product
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 */
abstract class CoreController extends Controller
{
    /**
     * Get the form handler
     *
     * @param Form    $form    The form to display.
     * @param Request $request The request.
     *
     * @return FormHandler A default form handler
     */
    protected function getFormHandler(Form $form, Request $request)
    {
        $handler = new FormHandler(
            $form,
            $request,
            $this->container->get('doctrine')->getManager(),
            $this->container->get('validator'),
            $this->container->get('session')
        );

        return $handler;
    }

    /**
     * Check if user is granted
     *
     * @param array $roles The list of roles.
     *
     * @return void
     *
     * @throws AccessDeniedException
     */
    protected function userIsGranted(array $roles)
    {
        $sc = $this->container->get('security.context');

        $isGranted = false;

        foreach ($roles as $role) {

            $isGranted = $isGranted || $sc->isGranted($role);
        }

        if (!($isGranted)) {
            throw new AccessDeniedException(
                'You are not allowed to access this resource'
            );
        }
    }

    /**
     * Set product in context
     *
     * @param Product $product The product.
     *
     * @return void
     */
    protected function setCurrentProduct(Product $product)
    {
        $logger = $this->get('monolog.logger.uctx');
        $logger->debug('CoreController->setCurrentProduct');

        $serviceUpdate = $this->container->get('map3_user.updatecontext4user');
        $serviceUpdate->setCurrentProduct($product);
    }

    /**
     * Return the current product from user context.
     *
     * @return Product
     */
    protected function getCurrentProductFromUser()
    {
        $logger = $this->get('monolog.logger.uctx');
        $logger->debug('CoreController->getCurrentProductFromUser');

        $user = $this->container->get('security.context')->getToken()
            ->getUser();

        $product = $user->getCurrentProduct();

        return $product;
    }

    /**
     * Unset product in context
     *
     * @return void
     */
    protected function unsetCurrentProduct()
    {
        $logger = $this->get('monolog.logger.uctx');
        $logger->debug('CoreController->unsetCurrentProduct');

        $serviceUpdate = $this->container->get('map3_user.updatecontext4user');
        $serviceUpdate->setCurrentProduct(null);
    }
}
