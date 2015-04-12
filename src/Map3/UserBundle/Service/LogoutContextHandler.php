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

namespace Map3\UserBundle\Service;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Logout handler class.
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
class LogoutContextHandler implements LogoutHandlerInterface
{
    /**
     * @var UserManagerInterface User manager
     */
    protected $userManager;

    /**
     * @var LoggerInterface Logger
     */
    protected $logger;

    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager The user manager
     * @param LoggerInterface      $logger      The logger
     */
    public function __construct(
        UserManagerInterface $userManager,
        LoggerInterface $logger
    ) {
        $this->userManager = $userManager;
        $this->logger      = $logger;
    }

    /**
     * This method is called by the LogoutListener when a user has requested
     * to be logged out. Usually, you would unset session variables, or remove
     * cookies, etc.
     *
     * @param Request        $request  Request
     * @param Response       $response Response
     * @param TokenInterface $token    The token
     */
    public function logout(
        Request $request,
        Response $response,
        TokenInterface $token
    ) {
        $user = $token->getUser();

        $this->logger->debug('Logout - unsetCurrentProduct()');

        $user->unsetCurrentProduct();
        $token->setAuthenticated(false);

        $this->userManager->updateUser($user);
    }
}
