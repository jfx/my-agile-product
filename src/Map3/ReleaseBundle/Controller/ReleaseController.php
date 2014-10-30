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
use Map3\CoreBundle\Controller\CoreController;
use Map3\ReleaseBundle\Entity\Release;
use Map3\ReleaseBundle\Form\ReleaseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Release controller class.
 *
 * @category  MyAgileProduct
 * @package   Release
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 */
class ReleaseController extends CoreController
{

    /**
     * Add a release.
     *
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_DM_MANAGER")
     */
    public function addAction(Request $request)
    {
        $product = $this->getCurrentProductFromUserWithReset();

        $release = new Release();
        $release->setProduct($product);

        $form = $this->createForm(new ReleaseType($this->container), $release);
        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {

            $id = $release->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Release added successfully !');

            return $this->redirect(
                $this->generateUrl('release_view', array('id' => $id))
            );
        }

        return $this->render(
            'Map3ReleaseBundle:Release:add.html.twig',
            array(
                'form' => $form->createView(),
                'product' => $product
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
        $this->setCurrentRelease($release, array('ROLE_DM_GUEST'));

        $releaseType = new ReleaseType($this->container);
        $releaseType->setDisabled();
        $form = $this->createForm($releaseType, $release);

        return $this->render(
            'Map3ReleaseBundle:Release:view.html.twig',
            array(
                'form' => $form->createView(),
                'release' => $release
            )
        );
    }

    /**
     * Edit a release
     *
     * @param Release $release The release to edit
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Release $release, Request $request)
    {
        $this->setCurrentRelease($release, array('ROLE_DM_MANAGER'));

        $form = $this->createForm(new ReleaseType($this->container), $release);

        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {

            $id = $release->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Release edited successfully !');

            return $this->redirect(
                $this->generateUrl('release_view', array('id' => $id))
            );
        }
        return $this->render(
            'Map3ReleaseBundle:Release:edit.html.twig',
            array(
                'form' => $form->createView(),
                'release' => $release
            )
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
        $this->setCurrentRelease($release, array('ROLE_DM_MANAGER'));

        if ($this->get('request')->getMethod() == 'POST') {

            $this->unsetCurrentRelease();

            $em = $this->getDoctrine()->getManager();
            $em->remove($release);

            try {
                $em->flush();

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
            array(
                'form' => $form->createView(),
                'release' => $release
            )
        );
    }

    /**
     * Tab for release
     *
     * @param string $activeTab The active tab
     *
     * @return Response A Response instance
     */
    public function tabsAction($activeTab)
    {
        $release = $this->getCurrentReleaseFromUserWithReset(false);

        $child = array();

        $entityManager = $this->container->get('doctrine')->getManager();
        
        $repositoryBln = $entityManager->getRepository(
            'Map3BaselineBundle:Baseline'
        );

        $child['baselines']  = $repositoryBln->countBaselinesByRelease(
            $release
        );
        
        return $this->render(
            'Map3ReleaseBundle:Release:tabs.html.twig',
            array(
                'release' => $release,
                'child' => $child,
                'activeTab' => $activeTab
            )
        );
    }
}
