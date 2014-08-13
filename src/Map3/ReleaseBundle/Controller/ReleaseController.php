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

namespace Map3\ReleaseBundle\Controller;

use Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Form\FormHandler;
use Map3\ReleaseBundle\Entity\Release;
use Map3\ReleaseBundle\Form\ReleaseType;
use Map3\UserBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Release controller class.
 *
 * @category  MyAgileRelease
 * @package   Release
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 */
class ReleaseController extends Controller
{

    /**
     * Add a release.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_DM_MANAGER")
     */
    public function addAction()
    {
        $sc = $this->container->get('security.context');
        $user = $sc->getToken()->getUser();
        $product = $user->getCurrentProduct();

        // Low probability If you have not a product,
        // you have not a ROLE_DM -> Error 403
        if ($product === null) {
            return $this->redirect($this->generateUrl('product_index'));
        }
        $release = new Release();
        $release->setProduct($product);

        $form   = $this->createForm(
            new ReleaseType($this->container),
            $release
        );

        $handler = new FormHandler(
            $form,
            $this->getRequest(),
            $this->container
        );

        if ($handler->process()) {

            $id = $release->getId();

            $service = $this->container->get('map3_user.updatecontext4user');
            $service->refreshAvailableProducts4UserId($user->getId());
            $sc->getToken()->setAuthenticated(false);

            $this->get('session')->getFlashBag()
                ->add('success', 'Release added successfully !');

            return $this->redirect(
                $this->generateUrl('release_view', array('id' => $id))
            );
        }

        $service = $this->container->get('map3_product.productinfo');
        $child   = $service->getChildCount($product);

        return $this->render(
            'Map3ReleaseBundle:Release:add.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product,
                'child' => $child
            )
        );
    }

    /**
     * View a release.
     *
     * @param Release $release The release to view.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction(Release $release)
    {
        $service = $this->container->get('map3_user.updatecontext4user');
        $service->setCurrentRelease($release);

        $releaseType = new ReleaseType($this->container);
        $releaseType->setDisabled();
        $form = $this->createForm($releaseType, $release);

        return $this->render(
            'Map3ReleaseBundle:Release:view.html.twig',
            array('form' => $form->createView(), 'release' => $release)
        );
    }

    /**
     * Edit a release
     *
     * @param Release $release The release to edit.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Release $release)
    {
        $service = $this->container->get('map3_user.updatecontext4user');
        $service->setCurrentRelease($release);

        $this->checkManagerRole();

        $form = $this->createForm(new ReleaseType($this->container), $release);

        $handler = new FormHandler(
            $form,
            $this->getRequest(),
            $this->container
        );

        if ($handler->process()) {

            $id = $release->getId();

            $sc = $this->container->get('security.context');
            $user = $sc->getToken()->getUser();
            $service->refreshAvailableProducts4UserId($user->getId());
            $sc->getToken()->setAuthenticated(false);

            $this->get('session')->getFlashBag()
                ->add('success', 'Release edited successfully !');

            return $this->redirect(
                $this->generateUrl('release_view', array('id' => $id))
            );
        }

        return $this->render(
            'Map3ReleaseBundle:Release:edit.html.twig',
            array('form' => $form->createView(), 'release' => $release)
        );
    }

    /**
     * Delete a release
     *
     * @param Release $release The release to delete.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function delAction(Release $release)
    {
        $service = $this->container->get('map3_user.updatecontext4user');
        $service->setCurrentRelease($release);

        $this->checkManagerRole();

        if ($this->get('request')->getMethod() == 'POST') {

            $service->setCurrentRelease(null);

            $em = $this->getDoctrine()->getManager();
            $em->remove($release);

            try {
                $em->flush();

                $this->refreshAvailableProducts();

                $this->get('session')->getFlashBag()
                    ->add('success', 'Release removed successfully !');

                return $this->redirect(
                    $this->generateUrl('pdt-release_index')
                );

            } catch (Exception $e) {

                $this->get('session')->getFlashBag()->add(
                    'danger',
                    'Impossible to remove this item'
                    .' - Integrity constraint violation !'
                );

                // With exception entity manager is closed.
                return $this->redirect(
                    $this->generateUrl(
                        'release_del',
                        array('id' => $release->getId())
                    )
                );
            }
        }

        $releaseType = new ReleaseType($this->container);
        $releaseType->setDisabled();
        $form = $this->createForm($releaseType, $release);

        return $this->render(
            'Map3ReleaseBundle:Release:del.html.twig',
            array('form' => $form->createView(), 'release' => $release)
        );
    }

    /**
     * Refresh available products displayed in select box
     *
     * @return void
     *
     */
    private function refreshAvailableProducts()
    {
        $sc = $this->container->get('security.context');
        $user = $sc->getToken()->getUser();

        $service = $this->container->get('map3_user.updatecontext4user');
        $service->refreshAvailableProducts4UserId($user->getId());

        $sc->getToken()->setAuthenticated(false);
    }

    /**
     * Check role of user
     *
     * @return void
     *
     */
    private function checkManagerRole()
    {
        $sc = $this->container->get('security.context');

        if (!($sc->isGranted(Role::MANAGER_ROLE))) {

            throw new AccessDeniedException(
                'You are not allowed to access this resource'
            );
        }
    }
}
