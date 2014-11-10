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

use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\BaselineBundle\Entity\Baseline;
use Map3\BaselineBundle\Entity\Reference;
use Map3\BaselineBundle\Form\ReferenceType;
use Map3\CoreBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;
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
class ReferenceController extends CoreController
{
    /**
     * List of references
     *
     * @param Baseline $baseline The baseline
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction(Baseline $baseline)
    {
        $this->setCurrentBaseline($baseline, array('ROLE_DM_GUEST'));

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3BaselineBundle:Reference');

        $refs = $repository->findReferencesByBaseline($baseline);

        return $this->render(
            'Map3BaselineBundle:Reference:index.html.twig',
            array(
                'refs'     => $refs,
                'baseline' => $baseline
            )
        );
    }

    /**
     * Add a reference.
     *
     * @param Baseline $baseline The baseline
     * @param Request  $request  The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function addAction(Baseline $baseline, Request $request)
    {
        $this->setCurrentBaseline($baseline, array('ROLE_DM_USERPLUS'));

        $ref = new Reference();
        $ref->setBaseline($baseline);

        $form = $this->createForm(new ReferenceType(), $ref);
        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {

            $this->get('session')->getFlashBag()
                ->add('success', 'Reference added successfully !');

            return $this->redirect(
                $this->generateUrl(
                    'bln-refs_index',
                    array('id' => $baseline->getId())
                )
            );
        }

        return $this->render(
            'Map3BaselineBundle:Reference:add.html.twig',
            array(
                'form'     => $form->createView(),
                'baseline' => $baseline
            )
        );
    }

    /**
     * Edit a reference
     *
     * @param Reference $reference The reference to edit
     * @param Request   $request   The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Reference $reference, Request $request)
    {
        $baseline = $reference->getBaseline();
        $this->setCurrentBaseline($baseline, array('ROLE_DM_USERPLUS'));

        $form = $this->createForm(new ReferenceType(), $reference);
        $handler = $this->getFormHandler($form, $request);

        if ($handler->process()) {

            $this->get('session')->getFlashBag()
                ->add('success', 'Reference edited successfully !');

            return $this->redirect(
                $this->generateUrl(
                    'bln-refs_index',
                    array('id' => $baseline->getId())
                )
            );
        }

        return $this->render(
            'Map3BaselineBundle:Reference:edit.html.twig',
            array(
                'form'     => $form->createView(),
                'baseline' => $baseline
            )
        );
    }

    /**
     * Delete a reference
     *
     * @param Reference $reference The reference to edit
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function delAction(Reference $reference)
    {
        $baseline = $reference->getBaseline();
        $this->setCurrentBaseline($baseline, array('ROLE_DM_USERPLUS'));

        if ($this->get('request')->getMethod() == 'POST') {

            $em = $this->getDoctrine()->getManager();
            $em->remove($reference);
            $em->flush();

            $this->get('session')->getFlashBag()
                ->add('success', 'Reference removed successfully !');

            return $this->redirect(
                $this->generateUrl(
                    'bln-refs_index',
                    array('id' => $baseline->getId())
                )
            );
        }

        $refType = new ReferenceType();
        $refType->setDisabled();
        $form = $this->createForm($refType, $reference);

        return $this->render(
            'Map3BaselineBundle:Reference:del.html.twig',
            array(
                'form'     => $form->createView(),
                'baseline' => $baseline
            )
        );
    }
}
