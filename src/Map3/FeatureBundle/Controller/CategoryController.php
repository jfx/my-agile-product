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

use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Controller\AbstractCoreController;
use Map3\FeatureBundle\Entity\Category;
use Map3\FeatureBundle\Form\CategoryType;
use Map3\UserBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Category controller class.
 *
 * @category  MyAgileProduct
 * @package   Feature
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2015 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 */
class CategoryController extends AbstractCoreController
{
    /**
     * Display node details on right panel
     *
     * @param Category $category The category to display
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function viewAction(Category $category) {
        
        $baseline = $category->getBaseline();
        $this->setCurrentBaseline($baseline, array(Role::GUEST_ROLE));

        $categoryType = new CategoryType();
        $categoryType->setDisabled();
        $form = $this->createForm($categoryType, $category);

        return $this->render(
            'Map3FeatureBundle:Category:view.html.twig',
            array(
                'form'     => $form->createView(),
                'category' => $category,
            )
        );
    }
    
    /**
     * Edit a category node on right panel
     *
     * @param Category $category The category to display
     * @param Request  $request  The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Category $category, Request $request) {
        
        $baseline = $category->getBaseline();
        
        $this->setCurrentBaseline(
            $baseline,
            array(Role::USERPLUS_ROLE, Role::BLN_OPEN_ROLE)
        );

        $form = $this->createForm(new CategoryType(), $category);

        $handler = $this->getFormHandler($form, $request);
        
        if ($handler->process()) {
            $id = $category->getId();

            $this->get('session')->getFlashBag()
                ->add('success', 'Category edited successfully !');

            return $this->redirect(
                $this->generateUrl('bln-cat_view', array('id' => $id))
            );
        }
        return $this->render(
            'Map3FeatureBundle:Category:edit.html.twig',
            array(
                'form'     => $form->createView(),
                'category' => $category,
            )
        );
    }
}
