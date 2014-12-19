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

namespace Map3\UserBundle\Form;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManager;
use Map3\CoreBundle\Form\FormHandler;
use Map3\UserBundle\Service\PasswordFactoryService;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validator\LegacyValidator;

/**
 * Form handler class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class UserFormHandler extends FormHandler
{
    /**
     * @var PasswordFactoryService Password factory
     */
    protected $passwordFactory;

    /**
     * @var UserManager User manager
     */
    protected $userManager;

    /**
     * Constructor
     *
     * @param Form                   $form        Form
     * @param Request                $request     Http request
     * @param EntityManager          $em          Entity manager
     * @param LegacyValidator        $validator   Validator
     * @param Session                $session     Session
     * @param PasswordFactoryService $passFactory Password factory
     * @param UserManager            $um          User manager
     */
    public function __construct(
        Form $form,
        Request $request,
        EntityManager $em,
        LegacyValidator $validator,
        Session $session,
        PasswordFactoryService $passFactory,
        UserManager $um
    ) {
        parent::__construct(
            $form,
            $request,
            $em,
            $validator,
            $session
        );
        $this->passwordFactory = $passFactory;
        $this->userManager     = $um;
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
        // If add user without password, then set a default password.
        $route = $this->request->get('_route');

        if (($route == 'user_add') && ($entity->getPlainPassword() === null)) {
            $password = $this->passwordFactory->generatePassword();
            $entity->setPlainPassword($password);
        }
        $this->userManager->updateUser($entity);
    }
}
