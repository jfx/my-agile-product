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

namespace Map3\FeatureBundle\Controller;

use Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Controller\AbstractJsonCoreController;
use Map3\FeatureBundle\Entity\Feature;
use Map3\FeatureBundle\Form\FeatureType;
use Map3\UserBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Feature controller class.
 *
 * @category  MyAgileProduct
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2015 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class FeatureController extends AbstractJsonCoreController
{
    /**
     * Display node details on right panel.
     *
     * @param Feature $feature The feature to display
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction(Feature $feature)
    {
        $baseline = $feature->getBaseline();
        $this->setCurrentBaseline($baseline, array(Role::GUEST_ROLE));

        $featureType = new FeatureType();
        $featureType->setDisabled();
        $form = $this->createForm($featureType, $feature);

        return $this->render(
            'Map3FeatureBundle:Feature:view.html.twig',
            array(
                'form' => $form->createView(),
                'feature' => $feature,
            )
        );
    }

    /**
     * Edit a feature node on right panel.
     *
     * @param Feature $feature The feature to display
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Feature $feature, Request $request)
    {
        $baseline = $feature->getBaseline();

        try {
            $this->setCurrentBaseline(
                $baseline,
                array(Role::USERPLUS_ROLE, Role::BLN_OPEN_ROLE)
            );
        } catch (Exception $e) {
            return $this->jsonResponseFactory(403, $e->getMessage());
        }
        $form = $this->createForm(new FeatureType(), $feature);

        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {
            $this->get('session')->getFlashBag()
                ->add('success', 'Feature edited successfully !');

            return $this->render(
                'Map3FeatureBundle:Feature:refresh.html.twig',
                array(
                    'feature' => $feature,
                    'parentNodeId' => null,
                )
            );
        }

        return $this->render(
            'Map3FeatureBundle:Feature:edit.html.twig',
            array(
                'form' => $form->createView(),
                'feature' => $feature,
            )
        );
    }

    /**
     * Delete a feature node on right panel.
     *
     * @param Feature $feature The feature to delete
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function delAction(Feature $feature, Request $request)
    {
        $baseline = $feature->getBaseline();
        $this->setCurrentBaseline(
            $baseline,
            array(Role::USERPLUS_ROLE, Role::BLN_OPEN_ROLE)
        );

        if ($request->isMethod('POST')) {
            $nodeId = $feature->getNodeId();

            $em = $this->getDoctrine()->getManager();
            $em->remove($feature);
            $em->flush();

            $this->get('session')->getFlashBag()
                ->add('success', 'Feature removed successfully !');

            return $this->render(
                'Map3CoreBundle::refreshTreeOnDel.html.twig',
                array('nodeId' => $nodeId)
            );
        }
        $featureType = new FeatureType();
        $featureType->setDisabled();
        $form = $this->createForm($featureType, $feature);

        return $this->render(
            'Map3FeatureBundle:Feature:del.html.twig',
            array(
                'form' => $form->createView(),
                'feature' => $feature,
            )
        );
    }
}
