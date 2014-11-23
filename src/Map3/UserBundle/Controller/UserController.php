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

namespace Map3\UserBundle\Controller;

use Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Controller\AbstractCoreController;
use Map3\UserBundle\Form\UserFormHandler;
use Map3\UserBundle\Form\UserPasswordType;
use Map3\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 */
class UserController extends AbstractCoreController
{
    /**
     * List of users
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
            ->getRepository('Map3UserBundle:User');

        $users = $repository->findAllOrderByNameFirstname();

        if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->render(
                'Map3UserBundle:User:index.html.twig',
                array('users' => $users)
            );
        } else {
            return $this->render(
                'Map3UserBundle:User:indexlight.html.twig',
                array('users' => $users)
            );
        }
    }

    /**
     * Add a user
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

        $userManager = $this->get('fos_user.user_manager');
        $user        = $userManager->createUser();
        $form        = $this->createForm(new UserType(), $user);

        $user->setEnabled(true);

        $handler = new UserFormHandler(
            $form,
            $request,
            $this->container->get('doctrine')->getManager(),
            $this->container->get('validator'),
            $this->container->get('session'),
            $this->container->get('map3_user.passwordFactoryService'),
            $userManager
        );

        if ($handler->process()) {
            $id = $user->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'User added successfully !');

            return $this->redirect(
                $this->generateUrl('user_view', array('id' => $id))
            );
        }

        return $this->render(
            'Map3UserBundle:User:add.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * View a user profile.
     *
     * @param int $id The user id.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function viewAction($id)
    {
        $this->unsetCurrentProduct();

        $userManager = $this->get('fos_user.user_manager');

        if (! $user = $userManager->findUserBy(array('id' => $id))) {
            throw $this->createNotFoundException('User[id='.$id.'] not found');
        }

        $userType = new UserType();
        $userType->setDisabled();
        $form = $this->createForm($userType, $user);

        return $this->render(
            'Map3UserBundle:User:view.html.twig',
            array('form' => $form->createView(), 'user' => $user)
        );
    }

    /**
     * View user's roles.
     *
     * @param int $id The user id.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function viewroleAction($id)
    {
        $this->unsetCurrentProduct();

        $userManager = $this->get('fos_user.user_manager');

        if (! $user = $userManager->findUserBy(array('id' => $id))) {
            throw $this->createNotFoundException('User[id='.$id.'] not found');
        }

        $repository = $this->getDoctrine()->getManager()->getRepository(
            'Map3UserBundle:UserPdtRole'
        );
        $availableProducts = $repository->findAvailableProductsByUser($user);

        return $this->render(
            'Map3UserBundle:User:viewrole.html.twig',
            array('user' => $user, 'products' => $availableProducts)
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
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function editAction($id, Request $request)
    {
        $this->unsetCurrentProduct();

        $userManager = $this->get('fos_user.user_manager');

        if (! $user = $userManager->findUserBy(array('id' => $id))) {
            throw $this->createNotFoundException('User[id='.$id.'] not found');
        }
        $form    = $this->createForm(new UserType(), $user);

        $handler = new UserFormHandler(
            $form,
            $request,
            $this->container->get('doctrine')->getManager(),
            $this->container->get('validator'),
            $this->container->get('session'),
            $this->container->get('map3_user.passwordFactoryService'),
            $userManager
        );

        if ($handler->process()) {
            $this->get('session')->getFlashBag()
                ->add('success', 'User edited successfully !');

            return $this->redirect(
                $this->generateUrl('user_view', array('id' => $id))
            );
        }

        return $this->render(
            'Map3UserBundle:User:edit.html.twig',
            array('form' => $form->createView(), 'user' => $user)
        );
    }

    /**
     * Delete a user
     *
     * @param int $id The user id.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function delAction($id)
    {
        $this->unsetCurrentProduct();

        $userManager = $this->get('fos_user.user_manager');

        if (! $user = $userManager->findUserBy(array('id' => $id))) {
            throw $this->createNotFoundException('User[id='.$id.'] not found');
        }

        if ($this->get('request')->getMethod() == 'POST') {
            try {
                $userManager->deleteUser($user);

                $success = true;
            } catch (Exception $e) {
                $success = false;

                $this->get('session')->getFlashBag()
                    ->add(
                        'danger',
                        'Impossible to remove this item'
                        .' - Integrity constraint violation !'
                    );
            }
            if ($success) {
                $this->get('session')->getFlashBag()
                    ->add('success', 'User removed successfully !');

                return $this->redirect($this->generateUrl('user_index'));
            }
        }

        $userType = new UserType();
        $userType->setDisabled();
        $form = $this->createForm($userType, $user);

        return $this->render(
            'Map3UserBundle:User:del.html.twig',
            array('form' => $form->createView(), 'user' => $user)
        );
    }

    /**
     * Display own profile
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function profileAction()
    {
        $this->unsetCurrentProduct();

        $user = $this->container->get('security.context')
            ->getToken()->getUser();

        $userType = new UserType();
        $userType->setDisabled();
        $form = $this->createForm($userType, $user);

        return $this->render(
            'Map3UserBundle:User:profile.html.twig',
            array('form' => $form->createView(), 'user' => $user)
        );
    }

    /**
     * Change own password
     *
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function passwordAction(Request $request)
    {
        $this->unsetCurrentProduct();

        $user = $this->container->get('security.context')
            ->getToken()->getUser();

        $form  = $this->createForm(new UserPasswordType(), $user);

        $handler = new UserFormHandler(
            $form,
            $request,
            $this->container->get('doctrine')->getManager(),
            $this->container->get('validator'),
            $this->container->get('session'),
            $this->container->get('map3_user.passwordFactoryService'),
            $this->get('fos_user.user_manager')
        );

        if ($handler->process()) {
            $this->get('session')->getFlashBag()
                ->add('success', 'Password modified !');
        }

        return $this->render(
            'Map3UserBundle:User:password.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Display own role
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function roleAction()
    {
        $this->unsetCurrentProduct();

        $user = $this->container->get('security.context')
            ->getToken()->getUser();

        $repository = $this->getDoctrine()->getManager()->getRepository(
            'Map3UserBundle:UserPdtRole'
        );
        $availableProducts = $repository->findAvailableProductsByUser($user);

        return $this->render(
            'Map3UserBundle:User:role.html.twig',
            array('products' => $availableProducts)
        );
    }
}
