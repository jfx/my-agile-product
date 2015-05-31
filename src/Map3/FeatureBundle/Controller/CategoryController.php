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
use Map3\BaselineBundle\Entity\Baseline;
use Map3\CoreBundle\Controller\AbstractJsonCoreController;
use Map3\FeatureBundle\Entity\Category;
use Map3\FeatureBundle\Form\CategoryType;
use Map3\UserBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Category controller class.
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
class CategoryController extends AbstractJsonCoreController
{
    /**
     * Add a category.
     *
     * @param string  $nid     The parent id (baseline or category)
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function addAction($nid, Request $request)
    {
        try {
            $node = $this->getObjectFromNodeId($nid);
        } catch (Exception $e) {
            return $this->jsonResponseFactory(404, $e->getMessage());
        }
        $manager = $this->getDoctrine()->getManager();
        $catRepository = $manager->getRepository('Map3FeatureBundle:Category');

        if ($node instanceof Baseline) {
            $baseline = $node;
            $parent = $catRepository->findRootByBaseline($baseline);
        } elseif ($node instanceof Category) {
            $parent = $node;
            $baseline = $parent->getBaseline();
        } else {
            return $this->jsonResponseFactory(405, 'Operation not allowed');
        }
        try {
            $this->setCurrentBaseline(
                $baseline,
                array(Role::USERPLUS_ROLE, Role::BLN_OPEN_ROLE)
            );
        } catch (Exception $e) {
            return $this->jsonResponseFactory(403, $e->getMessage());
        }
        $category = new Category();
        $category->setBaseline($baseline);
        $category->setParent($parent);

        $categoryType = new CategoryType();
        $form = $this->createForm($categoryType, $category);
        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {
            $this->get('session')->getFlashBag()
                ->add('success', 'Category added successfully !');

            return $this->render(
                'Map3FeatureBundle:Category:refresh.html.twig',
                array(
                    'category' => $category,
                    'parentNodeId' => $nid,
                )
            );
        }
        $response = $this->render(
            'Map3FeatureBundle:Category:add.html.twig',
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
     * Display node details on right panel.
     *
     * @param Category $category The category to display
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction(Category $category)
    {
        $baseline = $category->getBaseline();
        $this->setCurrentBaseline($baseline, array(Role::GUEST_ROLE));

        $categoryType = new CategoryType();
        $categoryType->setDisabled();
        $form = $this->createForm($categoryType, $category);

        return $this->render(
            'Map3FeatureBundle:Category:view.html.twig',
            array(
                'form' => $form->createView(),
                'category' => $category,
            )
        );
    }

    /**
     * Edit a category node on right panel.
     *
     * @param Category $category The category to display
     * @param Request  $request  The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Category $category, Request $request)
    {
        $baseline = $category->getBaseline();

        try {
            $this->setCurrentBaseline(
                $baseline,
                array(Role::USERPLUS_ROLE, Role::BLN_OPEN_ROLE)
            );
        } catch (Exception $e) {
            return $this->jsonResponseFactory(403, $e->getMessage());
        }
        $form = $this->createForm(new CategoryType(), $category);

        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {
            $this->get('session')->getFlashBag()
                ->add('success', 'Category edited successfully !');

            return $this->render(
                'Map3FeatureBundle:Category:refresh.html.twig',
                array(
                    'category' => $category,
                    'parentNodeId' => null,
                )
            );
        }

        return $this->render(
            'Map3FeatureBundle:Category:edit.html.twig',
            array(
                'form' => $form->createView(),
                'category' => $category,
            )
        );
    }

    /**
     * Delete a category node on right panel.
     *
     * @param Category $category The category to display
     * @param Request  $request  The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function delAction(Category $category, Request $request)
    {
        $baseline = $category->getBaseline();
        $this->setCurrentBaseline(
            $baseline,
            array(Role::USERPLUS_ROLE, Role::BLN_OPEN_ROLE)
        );

        if ($request->isMethod('POST')) {
            $nodeId = $category->getNodeId();

            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

            $this->get('session')->getFlashBag()
                ->add('success', 'Category removed successfully !');

            return $this->render(
                'Map3FeatureBundle:Category:refreshDel.html.twig',
                array('nodeId' => $nodeId)
            );
        }
        $categoryType = new CategoryType();
        $categoryType->setDisabled();
        $form = $this->createForm($categoryType, $category);

        return $this->render(
            'Map3FeatureBundle:Category:del.html.twig',
            array(
                'form' => $form->createView(),
                'category' => $category,
            )
        );
    }
}
