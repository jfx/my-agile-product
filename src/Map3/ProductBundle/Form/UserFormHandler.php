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

namespace Map3\ProductBundle\Form;

use Doctrine\ORM\EntityManager;
use Map3\CoreBundle\Form\FormHandler;
use Map3\ProductBundle\Entity\Product;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validator\LegacyValidator;

/**
 * User form handler class.
 *
 * @category  MyAgileProduct
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class UserFormHandler extends FormHandler
{
    /**
     * @var Product Product
     */
    protected $product;

    /**
     * Constructor.
     *
     * @param Form            $form      Form
     * @param Request         $request   Http request
     * @param EntityManager   $em        Entity manager
     * @param LegacyValidator $validator Validator
     * @param Session         $session   Session
     * @param Product         $pdt       The product
     */
    public function __construct(
        Form $form,
        Request $request,
        EntityManager $em,
        LegacyValidator $validator,
        Session $session,
        Product $pdt
    ) {
        parent::__construct(
            $form,
            $request,
            $em,
            $validator,
            $session
        );
        $this->product = $pdt;
    }

    /**
     * For a submited form, valid it and update database.
     *
     * @return bool
     */
    public function process()
    {
        if ($this->request->isMethod('POST')) {
            $this->form->handleRequest($this->request);

            if ($this->form->isValid()) {
                $entity = $this->form->getData();
                $entity->setProduct($this->product);

                $this->onSuccess($entity);

                return true;
            } else {
                $this->session->getFlashBag()->add(
                    'danger',
                    'Integrity constraint violation !'
                );
            }
        }

        return false;
    }
}
