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
namespace Map3\ScenarioBundle\Controller;

use Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Controller\AbstractJsonCoreController;
use Map3\FeatureBundle\Entity\Feature;
use Map3\ScenarioBundle\Entity\Scenario;
use Map3\ScenarioBundle\Form\ScenarioType;
use Map3\UserBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Scenario controller class.
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
class ScenarioController extends AbstractJsonCoreController
{
    /**
     * Add a scenario.
     *
     * @param string  $nid     The parent id (scenario)
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function addAction($nid, Request $request)
    {
        try {
            $feature = $this->getObjectFromNodeId($nid);
        } catch (Exception $e) {
            return $this->jsonResponseFactory(404, $e->getMessage());
        }

        if (!($feature instanceof Feature)) {
            return $this->jsonResponseFactory(405, 'Operation not allowed');
        }
        $baseline = $feature->getBaseline();
        try {
            $this->setCurrentBaseline(
                $baseline,
                array(Role::DEFAULT_ROLE, Role::BLN_OPEN_ROLE)
            );
        } catch (Exception $e) {
            return $this->jsonResponseFactory(403, $e->getMessage());
        }
        $scenario = new Scenario();
        $scenario->setBaseline($baseline);
        $scenario->setFeature($feature);

        $repositoryStatus = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3ScenarioBundle:Status');
        $defaultStatus = $repositoryStatus->findDefaultStatus();
        $scenario->setStatus($defaultStatus);

        $steps = $this->container->getParameter('app.defaultSteps');
        $scenario->setSteps(html_entity_decode($steps));

        $scenarioType = new ScenarioType();
        $form = $this->createForm($scenarioType, $scenario);
        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {
            $this->get('session')->getFlashBag()
                ->add('success', 'Scenario added successfully !');

            return $this->render(
                'Map3ScenarioBundle:Scenario:refresh.html.twig',
                array(
                    'scenario' => $scenario,
                    'parentNodeId' => $nid,
                )
            );
        }
        $response = $this->render(
            'Map3ScenarioBundle:Scenario:add.html.twig',
            array(
                'form' => $form->createView(),
                'nodeId' => $nid,
            )
        );
        if ($request->isMethod('POST')) {
            return $response;
        } else {
            return $this->html2jsonResponse($response);
        }
    }

    /**
     * Get children of a scenario node.
     *
     * @param Scenario $scenario The scenario
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function childAction(Scenario $scenario)
    {
        $baseline = $scenario->getBaseline();
        $this->setCurrentBaseline($baseline, array(Role::GUEST_ROLE));

        $repo = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3ScenarioBundle:Test');

        $tests = $repo->findAllByBaselineScenarioId(
            $baseline,
            $scenario->getId()
        );

        return $this->render(
            'Map3ScenarioBundle:Scenario:children.json.twig',
            array(
                'tests' => $tests,
            )
        );
    }

    /**
     * Display node details on right panel.
     *
     * @param Scenario $scenario The scenario to display
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction(Scenario $scenario)
    {
        $baseline = $scenario->getBaseline();
        $this->setCurrentBaseline($baseline, array(Role::GUEST_ROLE));

        $scenarioType = new ScenarioType();
        $scenarioType->setDisabled();
        $form = $this->createForm($scenarioType, $scenario);

        return $this->render(
            'Map3ScenarioBundle:Scenario:view.html.twig',
            array(
                'form' => $form->createView(),
                'scenario' => $scenario,
            )
        );
    }

    /**
     * Edit a scenario node on right panel.
     *
     * @param Scenario $scenario The scenario to edit
     * @param Request  $request  The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Scenario $scenario, Request $request)
    {
        $baseline = $scenario->getBaseline();

        try {
            $this->setCurrentBaseline(
                $baseline,
                array(Role::DEFAULT_ROLE, Role::BLN_OPEN_ROLE)
            );
        } catch (Exception $e) {
            return $this->jsonResponseFactory(403, $e->getMessage());
        }
        $form = $this->createForm(new ScenarioType(), $scenario);

        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {
            $this->get('session')->getFlashBag()
                ->add('success', 'Scenario edited successfully !');

            return $this->render(
                'Map3ScenarioBundle:Scenario:refresh.html.twig',
                array(
                    'scenario' => $scenario,
                    'parentNodeId' => null,
                )
            );
        }

        return $this->render(
            'Map3ScenarioBundle:Scenario:edit.html.twig',
            array(
                'form' => $form->createView(),
                'scenario' => $scenario,
            )
        );
    }

    /**
     * Delete a scenario node on right panel.
     *
     * @param Scenario $scenario The scenario to delete
     * @param Request  $request  The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function delAction(Scenario $scenario, Request $request)
    {
        $baseline = $scenario->getBaseline();
        $this->setCurrentBaseline(
            $baseline,
            array(Role::DEFAULT_ROLE, Role::BLN_OPEN_ROLE)
        );

        if ($request->isMethod('POST')) {
            $nodeId = $scenario->getNodeId();

            $em = $this->getDoctrine()->getManager();
            $em->remove($scenario);
            $em->flush();

            $this->get('session')->getFlashBag()
                ->add('success', 'Scenario removed successfully !');

            return $this->render(
                'Map3CoreBundle::refreshTreeOnDel.html.twig',
                array('nodeId' => $nodeId)
            );
        }
        $scenarioType = new ScenarioType();
        $scenarioType->setDisabled();
        $form = $this->createForm($scenarioType, $scenario);

        return $this->render(
            'Map3ScenarioBundle:Scenario:del.html.twig',
            array(
                'form' => $form->createView(),
                'scenario' => $scenario,
            )
        );
    }
}
