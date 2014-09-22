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

namespace Map3\BaselineBundle\Controller;

use Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\BaselineBundle\Entity\Baseline;
use Map3\BaselineBundle\Form\BaselineType;
use Map3\CoreBundle\Form\FormHandler;
use Map3\UserBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Baseline controller class.
 *
 * @category  MyAgileProduct
 * @package   Baseline
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 */
class BaselineController extends Controller
{

    /**
     * Add a baseline.
     *
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_DM_USERPLUS")
     */
    public function addAction(Request $request)
    {
        $sc = $this->container->get('security.context');
        $user = $sc->getToken()->getUser();
        $release = $user->getCurrentRelease();

        if ($release === null) {
            return $this->redirect($this->generateUrl('pdt-release_index'));
        }
        $baseline = new Baseline();
        $baseline->setRelease($release);

        $form   = $this->createForm(
            new BaselineType($this->container),
            $baseline
        );

        $handler = new FormHandler(
            $form,
            $request,
            $this->container->get('doctrine')->getManager(),
            $this->container->get('validator'),
            $this->container->get('session')
        );

        if ($handler->process()) {

            $id = $baseline->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Baseline added successfully !');

            return $this->redirect(
                $this->generateUrl('baseline_view', array('id' => $id))
            );
        }

        $service = $this->container->get('map3_release.releaseinfo');
        $child   = $service->getChildCount($release);

        return $this->render(
            'Map3BaselineBundle:Baseline:add.html.twig',
            array(
                'form' => $form->createView(),
                'release' => $release,
                'child' => $child
            )
        );
    }

    /**
     * View a baseline.
     *
     * @param Baseline $baseline The baseline to view.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction(Baseline $baseline)
    {
        $service = $this->container->get('map3_user.updatecontext4user');
        $service->setCurrentBaseline($baseline);

        $baselineType = new BaselineType($this->container);
        $baselineType->setDisabled();
        $form = $this->createForm($baselineType, $baseline);

        $serviceInfo = $this->container->get('map3_baseline.baselineinfo');
        $child       = $serviceInfo->getChildCount($baseline);

        return $this->render(
            'Map3BaselineBundle:Baseline:view.html.twig',
            array(
                'form' => $form->createView(),
                'baseline' => $baseline,
                'child' => $child
            )
        );
    }

    /**
     * Edit a baseline
     *
     * @param Baseline $baseline The baseline to edit
     * @param Request  $request  The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Baseline $baseline, Request $request)
    {
        $service = $this->container->get('map3_user.updatecontext4user');
        $service->setCurrentBaseline($baseline);

        $this->checkUserPlusRole();

        $form = $this->createForm(
            new BaselineType($this->container),
            $baseline
        );

        $handler = new FormHandler(
            $form,
            $request,
            $this->container->get('doctrine')->getManager(),
            $this->container->get('validator'),
            $this->container->get('session')
        );

        if ($handler->process()) {

            $id = $baseline->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Baseline edited successfully !');

            return $this->redirect(
                $this->generateUrl('baseline_view', array('id' => $id))
            );
        }

        $serviceInfo = $this->container->get('map3_baseline.baselineinfo');
        $child       = $serviceInfo->getChildCount($baseline);

        return $this->render(
            'Map3BaselineBundle:Baseline:edit.html.twig',
            array(
                'form' => $form->createView(),
                'baseline' => $baseline,
                'child' => $child
            )
        );
    }

    /**
     * Delete a baseline
     *
     * @param Baseline $baseline The baseline to delete.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function delAction(Baseline $baseline)
    {
        $service = $this->container->get('map3_user.updatecontext4user');
        $service->setCurrentBaseline($baseline);

        $this->checkUserPlusRole();

        if ($this->get('request')->getMethod() == 'POST') {

            $service->setCurrentBaseline(null);

            $em = $this->getDoctrine()->getManager();
            $em->remove($baseline);

            try {
                $em->flush();

                $this->get('session')->getFlashBag()
                    ->add('success', 'Baseline removed successfully !');

                return $this->redirect(
                    $this->generateUrl('rls-baseline_index')
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
                        'baseline_del',
                        array('id' => $baseline->getId())
                    )
                );
            }
        }

        $baselineType = new BaselineType($this->container);
        $baselineType->setDisabled();
        $form = $this->createForm($baselineType, $baseline);

        $serviceInfo = $this->container->get('map3_baseline.baselineinfo');
        $child       = $serviceInfo->getChildCount($baseline);

        return $this->render(
            'Map3BaselineBundle:Baseline:del.html.twig',
            array(
                'form' => $form->createView(),
                'baseline' => $baseline,
                'child' => $child
            )
        );
    }

    /**
     * Check role of user
     *
     * @return void
     *
     */
    private function checkUserPlusRole()
    {
        $sc = $this->container->get('security.context');

        if (!($sc->isGranted(Role::USERPLUS_ROLE))) {

            throw new AccessDeniedException(
                'You are not allowed to access this resource'
            );
        }
    }
}
