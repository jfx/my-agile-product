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
use Exception;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;

/**
 * Authentication listener class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class AuthenticationListener
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
     * Constructor
     *
     * @param EntityManager        $entityManager The doctrine entity manager.
     * @param UserManagerInterface $userManager   The user manager.
     */
    public function __construct(
        EntityManager $entityManager,
        UserManagerInterface $userManager
    ) {
        $this->entityManager = $entityManager;
        $this->userManager   = $userManager;
    }

    /**
     * When authentication succeed (login or cookie remember), refresh role for
     * the current product and refresh list of available products (select box).
     *
     * @param AuthenticationEvent $event The event object.
     *
     * @return void
     */
    public function onSecurityAuthenticationSuccess(AuthenticationEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();

        $repositoryUPR = $this->entityManager->getRepository(
            'Map3UserBundle:UserPdtRole'
        );
        $repositoryRl = $this->entityManager->getRepository(
            'Map3ReleaseBundle:Release'
        );

        // Update current product role
        $currentProduct = $user->getCurrentProduct();

        $user->unsetProductRole();

        if ($currentProduct !== null) {
            try {
                $userPdtRole = $repositoryUPR->findByUserIdProductId(
                    $user->getId(),
                    $currentProduct->getId()
                );
                $user->addRole($userPdtRole->getRole()->getId());
                $user->setCurrentRoleLabel($userPdtRole->getRole()->getLabel());
            } catch (Exception $e) {
            }
        }

        $releases = $repositoryRl->findAvailableReleasesByUser($user);

        $user->setAvailableReleases($releases);
        $this->userManager->updateUser($user);
    }
}
