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
use Map3\BaselineBundle\Entity\Reference;
use Map3\BaselineBundle\Form\ReferenceType;
use Map3\CoreBundle\Form\FormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reference controller class.
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
class ReferenceController extends Controller
{
    /**
     * List of references
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction()
    {
        $baseline = $this->getCurrentBaselineFromUser();

        if ($baseline === null) {
            return $this->redirect($this->generateUrl('rls-baseline_index'));
        }

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3BaselineBundle:Reference');

        $refs = $repository->findReferencesByBaseline($baseline);

        $service = $this->container->get('map3_baseline.baselineinfo');
        $child   = $service->getChildCount($baseline);

        return $this->render(
            'Map3BaselineBundle:Reference:index.html.twig',
            array(
                'refs'     => $refs,
                'baseline' => $baseline,
                'child'    => $child
            )
        );
    }

    /**
     * Add a reference.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_DM_USERPLUS")
     */
    public function addAction()
    {
        $baseline = $this->getCurrentBaselineFromUser();

        if ($baseline === null) {
            return $this->redirect($this->generateUrl('rls-baseline_index'));
        }

        $ref = new Reference();
        $ref->setBaseline($baseline);

        $form = $this->createForm(new ReferenceType(), $ref);

        $handler = new FormHandler(
            $form,
            $this->getRequest(),
            $this->container
        );

        if ($handler->process()) {

            $this->get('session')->getFlashBag()
                ->add('success', 'Reference added successfully !');

            return $this->redirect(
                $this->generateUrl('bln-ref_index')
            );
        }

        $service = $this->container->get('map3_baseline.baselineinfo');
        $child   = $service->getChildCount($baseline);

        return $this->render(
            'Map3BaselineBundle:Reference:add.html.twig',
            array(
                'form' => $form->createView(),
                'baseline' => $baseline,
                'child'    => $child
            )
        );
    }

    /**
     * Edit a reference
     *
     * @param int $id The reference id.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_DM_USERPLUS")
     */
    public function editAction($id)
    {
        $baseline = $this->getCurrentBaselineFromUser();

        if ($baseline === null) {
            return $this->redirect($this->generateUrl('rls-baseline_index'));
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Map3BaselineBundle:Reference');

        try {
            $ref = $repository->findByReferenceIdBaselineId(
                $id,
                $baseline->getId()
            );
        } catch (Exception $e) {
            throw $this->createNotFoundException(
                'Reference[id='.$id.'] not found for this baseline'
            );
        }

        $form = $this->createForm(new ReferenceType(), $ref);

        $handler = new FormHandler(
            $form,
            $this->getRequest(),
            $this->container
        );

        if ($handler->process()) {

            $this->get('session')->getFlashBag()
                ->add('success', 'Reference edited successfully !');

            return $this->redirect(
                $this->generateUrl('bln-ref_index')
            );
        }

        $serviceInfo = $this->container->get('map3_baseline.baselineinfo');
        $child       = $serviceInfo->getChildCount($baseline);

        return $this->render(
            'Map3BaselineBundle:Reference:edit.html.twig',
            array(
                'form' => $form->createView(),
                'baseline' => $baseline,
                'child'    => $child
            )
        );
    }

    /**
     * Delete a reference
     *
     * @param int $id The reference id.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_DM_USERPLUS")
     */
    public function delAction($id)
    {
        $baseline = $this->getCurrentBaselineFromUser();

        if ($baseline === null) {
            return $this->redirect($this->generateUrl('rls-baseline_index'));
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Map3BaselineBundle:Reference');

        try {
            $ref = $repository->findByReferenceIdBaselineId(
                $id,
                $baseline->getId()
            );
        } catch (Exception $e) {
            throw $this->createNotFoundException(
                'Reference[id='.$id.'] not found for this baseline'
            );
        }

        if ($this->get('request')->getMethod() == 'POST') {

            $em->remove($ref);
            $em->flush();

            $this->get('session')->getFlashBag()
                ->add('success', 'Reference removed successfully !');

            return $this->redirect(
                $this->generateUrl('bln-ref_index')
            );
        }

        $refType = new ReferenceType();
        $refType->setDisabled();
        $form = $this->createForm($refType, $ref);

        $serviceInfo = $this->container->get('map3_baseline.baselineinfo');
        $child       = $serviceInfo->getChildCount($baseline);

        return $this->render(
            'Map3BaselineBundle:Reference:del.html.twig',
            array(
                'form' => $form->createView(),
                'baseline' => $baseline,
                'child' => $child
            )
        );
    }

    /**
     * Return the current baseline from user context.
     *
     * @return Baseline
     */
    private function getCurrentBaselineFromUser()
    {
        $user = $this->container->get('security.context')->getToken()
            ->getUser();

        $baseline = $user->getCurrentBaseline();

        return $baseline;
    }
}
