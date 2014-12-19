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

namespace Map3\CoreBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validator\LegacyValidator;

/**
 * Form handler class.
 *
 * @category  MyAgileProduct
 * @package   Core
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class FormHandler
{
    /**
     * @var Form Form
     */
    protected $form;

    /**
     * @var Request Request
     */
    protected $request;

    /**
     * @var EntityManager Entity manager
     */
    protected $em;

    /**
     * @var LegacyValidator Validator
     *
     */
    protected $validator;

    /**
     * @var Session Session
     *
     */
    protected $session;

    /**
     * Constructor
     *
     * @param Form            $form      Form
     * @param Request         $request   Http request
     * @param EntityManager   $em        Entity manager
     * @param LegacyValidator $validator Validator
     * @param Session         $session   Session
     */
    public function __construct(
        Form $form,
        Request $request,
        EntityManager $em,
        LegacyValidator $validator,
        Session $session
    ) {
        $this->form      = $form;
        $this->request   = $request;
        $this->em        = $em;
        $this->validator = $validator;
        $this->session   = $session;
    }

    /**
     * For a submited form, valid it and update database.
     *
     * @return boolean
     */
    public function process()
    {
        if ($this->request->isMethod('POST')) {
            $this->form->handleRequest($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($this->form->getData());

                return true;
            } else {
                $errors = $this->validator->validate($this->form->getData());

                foreach ($errors as $error) {
                    $this->session->getFlashBag()->add(
                        'danger',
                        ucfirst($error->getPropertyPath())
                        .' : '.$error->getMessage()
                    );
                }
            }
        }

        return false;
    }

    /**
     * Save entity in database.
     *
     * @param mixed $entity Object entity.
     *
     * @return void
     */
    public function onSuccess($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
