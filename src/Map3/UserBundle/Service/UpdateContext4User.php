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
use Map3\ProductBundle\Entity\Product;
use Map3\ReleaseBundle\Entity\Release;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Update context service class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class UpdateContext4User
{
    /**
     * @var SecurityContextInterface S. Context
     *
     */
    protected $securityContext;

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
     * @param SecurityContextInterface $securityContext The security context.
     * @param EntityManager            $entityManager   The entity manager.
     * @param UserManagerInterface     $userManager     The user manager.
     */
    public function __construct(
        SecurityContextInterface $securityContext,
        EntityManager $entityManager,
        UserManagerInterface $userManager
    ) {
        $this->securityContext = $securityContext;
        $this->entityManager   = $entityManager;
        $this->userManager     = $userManager;
    }

    /**
     * Set the current product for a user and set role.
     *
     * @param Product|null $product          The product, if null unset product.
     * @param boolean      $resetCurrRelease Current release must be resetted.
     *
     * @return void
     */
    public function setCurrentProduct($product, $resetCurrRelease = true)
    {
        $user = $this->securityContext->getToken()->getUser();

        $user->unsetProductRole();

        if ($resetCurrRelease) {
            $user->unsetCurrentRelease();
        }

        if ($product === null) {
            $user->unsetCurrentProduct();
        } else {
            $user->setCurrentProduct($product);

            $repository = $this->entityManager->getRepository(
                'Map3UserBundle:UserPdtRole'
            );

            try {
                $userPdtRole = $repository->findByUserIdProductId(
                    $user->getId(),
                    $product->getId()
                );
                $user->addRole($userPdtRole->getRole()->getId());
                $this->securityContext->getToken()->setAuthenticated(false);
                $user->setCurrentRoleLabel($userPdtRole->getRole()->getLabel());
            } catch (Exception $e) {
                $this->securityContext->getToken()->setAuthenticated(false);
            }
        }
        $this->userManager->updateUser($user);
    }

    /**
     * Set the current release and set product and role.
     *
     * @param Release|null $release The release, if null unset current release.
     *
     * @return void
     */
    public function setCurrentRelease($release)
    {
        $user = $this->securityContext->getToken()->getUser();

        if ($release === null) {
            $user->unsetCurrentRelease();
        } else {
            $user->setCurrentRelease($release);

            $product = $release->getProduct();
            $this->setCurrentProduct($product, false);
        }
    }

    /**
     * Refresh available products list.
     *
     * @param int $userId The user id.
     *
     * @return void
     */
    public function refreshAvailableProducts4UserId($userId)
    {
        if (! $user = $this->userManager->findUserBy(array('id' => $userId))) {
            throw $this->createNotFoundException(
                'User[id='.$userId.'] not found'
            );
        }
        $repository = $this->entityManager->getRepository(
            'Map3ReleaseBundle:Release'
        );

        $releases = $repository->findAvailableReleasesByUser($user);

        $user->setAvailableReleases($releases);
        $this->userManager->updateUser($user);
    }
}
