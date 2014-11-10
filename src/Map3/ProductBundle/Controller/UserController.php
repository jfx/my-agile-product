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
use Map3\CoreBundle\Controller\CoreController;
use Map3\ProductBundle\Form\UserTypeAdd;
use Map3\ProductBundle\Form\UserTypeEditDel;
use Map3\ProductBundle\Form\UserFormHandler;
use Map3\UserBundle\Entity\UserPdtRole;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller class.
 *
 * @category  MyAgileProduct
 * @package   Product
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class UserController extends CoreController
{
    /**
     * List of users for a product
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_DM_GUEST")
     */
    public function indexAction()
    {
        $product = $this->getCurrentProductFromUserWithReset();

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3UserBundle:UserPdtRole');

        $users = $repository->findUsersByProduct($product);

        return $this->render(
            'Map3ProductBundle:User:index.html.twig',
            array(
                'users' => $users,
                'product' => $product
            )
        );
    }

    /**
     * Add a user
     *
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN, ROLE_DM_MANAGER")
     */
    public function addAction(Request $request)
    {
        $product = $this->getCurrentProductFromUserWithReset();

        // Possible if role is super-admin
        if ($product === null) {
            return $this->redirect($this->generateUrl('product_index'));
        }

        $repositoryUser = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3UserBundle:User');

        $count = $repositoryUser->getCountAvailableUserByProduct($product);

        if ($count < 1) {

            $this->get('session')->getFlashBag()
                ->add('danger', 'No user to add !');

            return $this->redirect(
                $this->generateUrl('pdt-user_index')
            );
        }

        $userPdtRole = new UserPdtRole();

        $repositoryRole = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3UserBundle:Role');
        $defaultRole = $repositoryRole->findDefaultRole();
        $userPdtRole->setRole($defaultRole);

        $form = $this->createForm(new UserTypeAdd($product), $userPdtRole);

        $handler = new UserFormHandler(
            $form,
            $request,
            $this->container->get('doctrine')->getManager(),
            $this->container->get('validator'),
            $this->container->get('session'),
            $product
        );

        if ($handler->process()) {

            $this->get('session')->getFlashBag()
                ->add('success', 'User added successfully !');

            return $this->redirect(
                $this->generateUrl('pdt-user_index')
            );
        }

        return $this->render(
            'Map3ProductBundle:User:add.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product
            )
        );
    }

    /**
     * Edit a user
     *
     * @param int     $id      The user id
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN, ROLE_DM_MANAGER")
     */
    public function editAction($id, Request $request)
    {
        $product = $this->getCurrentProductFromUserWithReset();

        // Possible if role is super-admin
        if ($product === null) {
            return $this->redirect($this->generateUrl('product_index'));
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Map3UserBundle:UserPdtRole');

        try {
            $userPdtRole = $repository->findByUserIdProductId(
                $id,
                $product->getId()
            );
        } catch (Exception $e) {
            throw $this->createNotFoundException(
                'User[id='.$id.'] not found for this product'
            );
        }

        $form = $this->createForm(
            new UserTypeEditDel($product),
            $userPdtRole
        );

        $handler = new UserFormHandler(
            $form,
            $request,
            $this->container->get('doctrine')->getManager(),
            $this->container->get('validator'),
            $this->container->get('session'),
            $product
        );

        if ($handler->process()) {

            $this->get('session')->getFlashBag()
                ->add('success', 'User edited successfully !');

            return $this->redirect(
                $this->generateUrl('pdt-user_index')
            );
        }

        return $this->render(
            'Map3ProductBundle:User:edit.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product
            )
        );
    }

    /**
     * Delete a user
     *
     * @param int $id The userid.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN, ROLE_DM_MANAGER")
     */
    public function delAction($id)
    {
        $product = $this->getCurrentProductFromUserWithReset();

        // Possible if role is super-admin
        if ($product === null) {
            return $this->redirect($this->generateUrl('product_index'));
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Map3UserBundle:UserPdtRole');

        try {
            $userPdtRole = $repository->findByUserIdProductId(
                $id,
                $product->getId()
            );
        } catch (Exception $e) {
            throw $this->createNotFoundException(
                'User[id='.$id.'] not found for this product'
            );
        }

        if ($this->get('request')->getMethod() == 'POST') {

            $em->remove($userPdtRole);

            try {
                $em->flush();

                $this->get('session')->getFlashBag()
                    ->add('success', 'User removed successfully !');

                return $this->redirect(
                    $this->generateUrl('pdt-user_index')
                );
            } catch (Exception $e) {

                $this->get('session')->getFlashBag()->add(
                    'danger',
                    'Impossible to remove this item'
                    .' - Integrity constraint violation !'
                );
            }
        }

        $userType = new UserTypeEditDel($product);
        $userType->setDisabled();
        $form = $this->createForm($userType, $userPdtRole);

        return $this->render(
            'Map3ProductBundle:User:del.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product
            )
        );
    }
}
