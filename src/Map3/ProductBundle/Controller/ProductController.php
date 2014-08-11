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

use Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Form\FormHandler;
use Map3\ProductBundle\Entity\Product;
use Map3\ProductBundle\Form\ProductType;
use Map3\UserBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
 *
 */
class ProductController extends Controller
{
    /**
     * List of products
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3ProductBundle:Product');

        $products = $repository->findAllOrderByName();

        return $this->render(
            'Map3ProductBundle:Product:index.html.twig',
            array('products' => $products)
        );
    }

    /**
     * Add a product
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function addAction()
    {
        $product = new Product();
        $form   = $this->createForm(new ProductType(), $product);

        $handler = new FormHandler(
            $form,
            $this->getRequest(),
            $this->container
        );

        if ($handler->process()) {

            $id = $product->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Product added successfully !');

            return $this->redirect(
                $this->generateUrl('product_view', array('id' => $id))
            );
        }

        return $this->render(
            'Map3ProductBundle:Product:add.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * View a product
     * 
     * @param Product $product The product to view.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction(Product $product)
    {
        $serviceUpdate = $this->container->get('map3_user.updatecontext4user');
        $serviceUpdate->setCurrentProduct($product);

        $serviceInfo = $this->container->get('map3_product.productinfo');
        $child       = $serviceInfo->getChildCount($product);

        $productType = new ProductType();
        $productType->setDisabled();
        $form = $this->createForm($productType, $product);

        return $this->render(
            'Map3ProductBundle:Product:view.html.twig',
            array(
                'form'      => $form->createView(),
                'product'   => $product,
                'child'     => $child
            )
        );
    }

    /**
     * Edit a product
     *
     * @param Product $product The product to edit.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Product $product)
    {
        $service = $this->container->get('map3_user.updatecontext4user');
        $service->setCurrentProduct($product);

        $sc = $this->container->get('security.context');
        $sc->isGranted(Role::MANAGER_ROLE);
                
        if (!($sc->isGranted('ROLE_SUPER_ADMIN')
            || $sc->isGranted(Role::MANAGER_ROLE))
        ) {
            throw new AccessDeniedException(
                'You are not allowed to access this resource'
            );
        }

        $form    = $this->createForm(new ProductType(), $product);

        $handler = new FormHandler(
            $form,
            $this->getRequest(),
            $this->container
        );

        if ($handler->process()) {

            $id = $product->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Product edited successfully !');

            return $this->redirect(
                $this->generateUrl('product_view', array('id' => $id))
            );
        }
        $serviceInfo = $this->container->get('map3_product.productinfo');
        $child       = $serviceInfo->getChildCount($product);
        
        return $this->render(
            'Map3ProductBundle:Product:edit.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product,
                'child' => $child
            )
        );
    }

    /**
     * Delete a product
     *
     * @param Product $product The product to delete.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function delAction(Product $product)
    {
        $service = $this->container->get('map3_user.updatecontext4user');

        if ($this->get('request')->getMethod() == 'POST') {

            $em = $this->getDoctrine()->getManager();

            $service->setCurrentProduct(null);

            $em->remove($product);

            try {
                $em->flush();

                $this->get('session')->getFlashBag()
                    ->add('success', 'Product removed successfully !');

                return $this->redirect(
                    $this->generateUrl('product_index')
                );

            } catch (Exception $e) {

                $this->get('session')->getFlashBag()->add(
                    'danger',
                    'Impossible to remove this item'
                    .' - Integrity constraint violation !'
                );

                return $this->redirect(
                    $this->generateUrl(
                        'product_del',
                        array('id' => $product->getId())
                    )
                );
            }
        }
        $service->setCurrentProduct($product);

        $productType = new ProductType();
        $productType->setDisabled();
        $form = $this->createForm($productType, $product);

        $serviceInfo = $this->container->get('map3_product.productinfo');
        $child       = $serviceInfo->getChildCount($product);
        
        return $this->render(
            'Map3ProductBundle:Product:del.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product,
                'child' => $child
            )
        );
    }
}
