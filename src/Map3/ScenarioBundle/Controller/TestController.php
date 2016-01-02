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
use Map3\ScenarioBundle\Entity\Scenario;
use Map3\ScenarioBundle\Entity\Test;
use Map3\ScenarioBundle\Form\TestFormHandler;
use Map3\ScenarioBundle\Form\TestType;
use Map3\UserBundle\Entity\Role;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test controller class.
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
class TestController extends AbstractJsonCoreController
{
    /**
     * Add a test.
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
            $scenario = $this->getObjectFromNodeId($nid);
        } catch (Exception $e) {
            return $this->jsonResponseFactory(404, $e->getMessage());
        }

        if (!($scenario instanceof Scenario)) {
            return $this->jsonResponseFactory(405, 'Operation not allowed');
        }
        $baseline = $scenario->getBaseline();
        try {
            $this->setCurrentBaseline(
                $baseline,
                array(Role::DEFAULT_ROLE, Role::BLN_OPEN_ROLE)
            );
        } catch (Exception $e) {
            return $this->jsonResponseFactory(403, $e->getMessage());
        }
        $test = new Test();
        $test->setBaseline($baseline);
        $test->setScenario($scenario);
        $test->fixStepsResultsMissing($scenario->getStepsCount());

        $product = $baseline->getRelease()->getProduct();
        $availableResults = $this->getAvailableResults();
        $testType = new TestType($product, $availableResults);

        $form = $this->createForm($testType, $test);
        $handler = $this->getTestFormHandler($form, $request);

        if ($handler->process()) {
            if ($this->updateSenarioStatus($scenario)) {
                $msg = 'Test added successfully ! Please, reload the tree'
                    .' to see the new status of scenario.';
                $this->get('session')->getFlashBag()
                    ->add('success', $msg);
            } else {
                $this->get('session')->getFlashBag()
                    ->add('success', 'Test added successfully !');
            }

            return $this->render(
                'Map3ScenarioBundle:Test:refresh.html.twig',
                array(
                    'test' => $test,
                    'parentNodeId' => $nid,
                )
            );
        }
        $response = $this->render(
            'Map3ScenarioBundle:Test:add.html.twig',
            array(
                'form' => $form->createView(),
                'nodeId' => $nid,
                'test' => $test,
                'steps' => $scenario->getFormatedArraySteps(),
            )
        );
        if ($request->isMethod('POST')) {
            return $response;
        } else {
            return $this->html2jsonResponse($response);
        }
    }

    /**
     * Display node details on right panel.
     *
     * @param Test $test The test to display
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction(Test $test)
    {
        $baseline = $test->getBaseline();
        $this->setCurrentBaseline($baseline, array(Role::GUEST_ROLE));
        $product = $baseline->getRelease()->getProduct();
        $scenario = $test->getScenario();
        $test->fixStepsResultsMissing($scenario->getStepsCount());

        $availableResults = $this->getAvailableResults();
        $testType = new TestType($product, $availableResults);
        $testType->setDisabled();
        $form = $this->createForm($testType, $test);

        return $this->render(
            'Map3ScenarioBundle:Test:view.html.twig',
            array(
                'form' => $form->createView(),
                'test' => $test,
                'steps' => $scenario->getFormatedArraySteps(),
            )
        );
    }

    /**
     * Edit a scenario node on right panel.
     *
     * @param Test    $test    The test to edit
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Test $test, Request $request)
    {
        $baseline = $test->getBaseline();

        try {
            $this->setCurrentBaseline(
                $baseline,
                array(Role::DEFAULT_ROLE, Role::BLN_OPEN_ROLE)
            );
        } catch (Exception $e) {
            return $this->jsonResponseFactory(403, $e->getMessage());
        }
        $product = $baseline->getRelease()->getProduct();
        $scenario = $test->getScenario();
        $test->fixStepsResultsMissing($scenario->getStepsCount());

        $availableResults = $this->getAvailableResults();
        $testType = new TestType($product, $availableResults);

        $form = $this->createForm($testType, $test);
        $handler = $this->getTestFormHandler($form, $request);

        if ($handler->process()) {
            if ($this->updateSenarioStatus($scenario)) {
                $msg = 'Test edited successfully ! Please, reload the tree'
                    .' to see the new status of scenario.';
                $this->get('session')->getFlashBag()
                    ->add('success', $msg);
            } else {
                $this->get('session')->getFlashBag()
                    ->add('success', 'Test edited successfully !');
            }

            return $this->render(
                'Map3ScenarioBundle:Test:refresh.html.twig',
                array(
                    'test' => $test,
                    'parentNodeId' => null,
                )
            );
        }

        return $this->render(
            'Map3ScenarioBundle:Test:edit.html.twig',
            array(
                'form' => $form->createView(),
                'test' => $test,
                'steps' => $scenario->getFormatedArraySteps(),
            )
        );
    }

    /**
     * Get available results.
     *
     * @return array
     */
    private function getAvailableResults()
    {
        $resultRepo = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3ScenarioBundle:Result');
        $availableResults = $resultRepo->getAllOrdered();

        return $availableResults;
    }

    /**
     * Get the test form handler.
     *
     * @param Form    $form    The form to display.
     * @param Request $request The request.
     *
     * @return TestFormHandler Test form handler
     */
    private function getTestFormHandler(Form $form, Request $request)
    {
        $handler = new TestFormHandler(
            $form,
            $request,
            $this->container->get('doctrine')->getManager(),
            $this->container->get('validator'),
            $this->container->get('session'),
            $this->container->get('map3_scenario.testService')
        );

        return $handler;
    }

    /**
     * Update scenario status.
     *
     * @param Scenario $scenario The scenario to update
     *
     * @return bool True if status of scenario has changed, false otherwise
     */
    private function updateSenarioStatus(Scenario $scenario)
    {
        $scenarioService = $this->container->get(
            'map3_scenario.scenarioService'
        );
        $prevScenarioStatus = $scenario->getStatus();

        $scenarioService->updateStatus($scenario);

        $em = $this->container->get('doctrine')->getManager();
        $em->persist($scenario);
        $em->flush();

        $scenarioStatus = $scenario->getStatus();

        return $prevScenarioStatus != $scenarioStatus;
    }
}
