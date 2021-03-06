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

use Doctrine\DBAL\DBALException;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Controller\AbstractCoreController;
use Map3\ProductBundle\Entity\Product;
use Map3\ProductBundle\Form\ProductType;
use Map3\UserBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Product controller class.
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
class ProductController extends AbstractCoreController
{
    /**
     * List of products.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        $this->unsetCurrentProduct();

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
     * Add a product.
     *
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function addAction(Request $request)
    {
        $this->unsetCurrentProduct();

        $product = new Product();
        $form = $this->createForm(new ProductType(), $product);

        $handler = $this->getFormHandler($form, $request);

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
     * View a product.
     *
     * @param Product $product The product to view.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction(Product $product)
    {
        $this->setCurrentProduct($product, array(Role::GUEST_ROLE));

        $productType = new ProductType();
        $productType->setDisabled();
        $form = $this->createForm($productType, $product);

        return $this->render(
            'Map3ProductBundle:Product:view.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product,
            )
        );
    }

    /**
     * Edit a product.
     *
     * @param Product $product The product to edit
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Product $product, Request $request)
    {
        $this->setCurrentProductAnyRole(
            $product,
            array('ROLE_SUPER_ADMIN', Role::MANAGER_ROLE)
        );

        $form = $this->createForm(new ProductType(), $product);
        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {
            $id = $product->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Product edited successfully !');

            return $this->redirect(
                $this->generateUrl('product_view', array('id' => $id))
            );
        }

        return $this->render(
            'Map3ProductBundle:Product:edit.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product,
            )
        );
    }

    /**
     * Delete a product.
     *
     * @param Product $product The product to delete
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function delAction(Product $product, Request $request)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $this->unsetCurrentProduct();

            $serviceRemove = $this->container->get(
                'map3_user.removeContextService'
            );
            $serviceRemove->removeProduct($product);

            $em->remove($product);

            try {
                $em->flush();

                $this->get('session')->getFlashBag()
                    ->add('success', 'Product removed successfully !');

                return $this->redirect(
                    $this->generateUrl('product_index')
                );
            } catch (DBALException $e) {
                $this->catchIntegrityConstraintViolation($e);

                return $this->redirect(
                    $this->generateUrl(
                        'product_del',
                        array('id' => $product->getId())
                    )
                );
            }
        }
        $this->setCurrentProduct($product, array('ROLE_SUPER_ADMIN'));

        $productType = new ProductType();
        $productType->setDisabled();
        $form = $this->createForm($productType, $product);

        return $this->render(
            'Map3ProductBundle:Product:del.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product,
            )
        );
    }

    /**
     * Tab for product.
     *
     * @param string $activeTab The active tab
     *
     * @return Response A Response instance
     */
    public function tabsAction($activeTab)
    {
        $product = $this->getCurrentProductFromUserWithReset(false);

        $child = array();

        $entityManager = $this->container->get('doctrine')->getManager();

        $repositoryRl = $entityManager->getRepository(
            'Map3ReleaseBundle:Release'
        );

        $repositoryUPR = $entityManager->getRepository(
            'Map3UserBundle:UserPdtRole'
        );

        $child['releases'] = $repositoryRl->countReleasesByProduct($product);
        $child['users'] = $repositoryUPR->countUsersByProduct($product);

        return $this->render(
            'Map3ProductBundle:Product:tabs.html.twig',
            array(
                'product' => $product,
                'child' => $child,
                'activeTab' => $activeTab,
            )
        );
    }
}
