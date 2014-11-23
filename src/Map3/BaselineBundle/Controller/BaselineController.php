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
use Map3\CoreBundle\Controller\AbstractCoreController;
use Map3\ReleaseBundle\Entity\Release;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
class BaselineController extends AbstractCoreController
{
    /**
     * Add a baseline.
     *
     * @param Release $release The release
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function addAction(Release $release, Request $request)
    {
        $this->setCurrentRelease($release, array('ROLE_DM_USERPLUS'));

        $baseline = new Baseline();
        $baseline->setRelease($release);

        $form   = $this->createForm(
            new BaselineType($this->container),
            $baseline
        );

        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {
            $id = $baseline->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Baseline added successfully !');

            return $this->redirect(
                $this->generateUrl('baseline_view', array('id' => $id))
            );
        }

        return $this->render(
            'Map3BaselineBundle:Baseline:add.html.twig',
            array(
                'form'    => $form->createView(),
                'release' => $release,
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
        $this->setCurrentBaseline($baseline, array('ROLE_DM_GUEST'));

        $baselineType = new BaselineType($this->container);
        $baselineType->setDisabled();
        $form = $this->createForm($baselineType, $baseline);

        return $this->render(
            'Map3BaselineBundle:Baseline:view.html.twig',
            array(
                'form'     => $form->createView(),
                'release'  => $baseline->getRelease(),
                'baseline' => $baseline,
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
        $this->setCurrentBaseline($baseline, array('ROLE_DM_USERPLUS'));

        $form = $this->createForm(
            new BaselineType($this->container),
            $baseline
        );

        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {
            $id = $baseline->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Baseline edited successfully !');

            return $this->redirect(
                $this->generateUrl('baseline_view', array('id' => $id))
            );
        }

        return $this->render(
            'Map3BaselineBundle:Baseline:edit.html.twig',
            array(
                'form'     => $form->createView(),
                'release'  => $baseline->getRelease(),
                'baseline' => $baseline,
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
        $this->setCurrentBaseline($baseline, array('ROLE_DM_USERPLUS'));

        $release = $baseline->getRelease();

        if ($this->get('request')->getMethod() == 'POST') {
            $this->unsetCurrentBaseline();

            $em = $this->getDoctrine()->getManager();
            $em->remove($baseline);

            try {
                $em->flush();

                $this->get('session')->getFlashBag()
                    ->add('success', 'Baseline removed successfully !');

                return $this->redirect(
                    $this->generateUrl(
                        'rls-baseline_index',
                        array('id' => $release->getId())
                    )
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

        return $this->render(
            'Map3BaselineBundle:Baseline:del.html.twig',
            array(
                'form'     => $form->createView(),
                'release'  => $release,
                'baseline' => $baseline,
            )
        );
    }

    /**
     * Tab for baseline
     *
     * @param string $activeTab The active tab
     *
     * @return Response A Response instance
     */
    public function tabsAction($activeTab)
    {
        $baseline = $this->getCurrentBaselineFromUserWithReset(false);

        $child = array();

        $entityManager = $this->container->get('doctrine')->getManager();

        $repositoryRef = $entityManager->getRepository(
            'Map3BaselineBundle:Reference'
        );

        $child['references']  = $repositoryRef->countReferencesByBaseline(
            $baseline
        );

        return $this->render(
            'Map3BaselineBundle:Baseline:tabs.html.twig',
            array(
                'baseline'  => $baseline,
                'child'     => $child,
                'activeTab' => $activeTab,
            )
        );
    }
}
