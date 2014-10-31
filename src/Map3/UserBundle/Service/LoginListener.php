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

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Psr\Log\LoggerInterface;

/**
 * Login listener class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class LoginListener
{
    /**
     * @var EntityManager Entity manager
     */
    protected $entityManager;

    /**
     * @var FOS\UserBundle\Model\UserManagerInterface User manager
     */
    protected $userManager;

    /**
     * @var Psr\Log\LoggerInterface Logger
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param EntityManager        $entityManager The doctrine entity manager
     * @param UserManagerInterface $userManager   The user manager
     * @param LoggerInterface      $logger        The logger
     */
    public function __construct(
        EntityManager $entityManager,
        UserManagerInterface $userManager,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->userManager   = $userManager;
        $this->logger        = $logger;
    }

    /**
     * When authentication succeed (login or cookie remember), unset context
     * of user and refresh list of available products (select box).
     *
     * @param InteractiveLoginEvent $event The event object.
     *
     * @return void
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->logger->debug('onSecurityInteractiveLogin');

        $token = $event->getAuthenticationToken();
        $user = $token->getUser();

        $user->unsetCurrentProduct();
        $token->setAuthenticated(false);

        $repositoryRl = $this->entityManager->getRepository(
            'Map3ReleaseBundle:Release'
        );

        $releases = $repositoryRl->findAvailableReleasesByUser($user);

        $user->setAvailableReleases($releases);
        $this->userManager->updateUser($user);
    }
}
