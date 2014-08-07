<?php
/**
 * LICENSE : This file is part of My Agile Project.
 *
 * My Agile Project is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * My Agile Project is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Map3\ProductBundle\Form;

use Map3\CoreBundle\Form\FormHandler;
use Map3\ProductBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * User form handler class.
 *
 * @category  MyAgileProduct
 * @package   Product
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproject.org
 * @since     3
 */
class UserFormHandler extends FormHandler
{
    /**
     * @var Product Product
     */
    protected $product;

    /**
     * Constructor
     *
     * @param Form               $form      Form.
     * @param Request            $request   Http request.
     * @param ContainerInterface $container Container
     * @param Product            $pdt       The product.
     */
    public function __construct(
        Form $form,
        Request $request,
        ContainerInterface $container,
        Product $pdt
    ) {
        parent::__construct($form, $request, $container);
        $this->product = $pdt;
    }

    /**
     * For a submited form, valid it and update database.
     *
     * @return boolean
     */
    public function process()
    {
        if ($this->request->getMethod() == 'POST') {

            $this->form->bind($this->request);

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
