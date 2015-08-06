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
use Doctrine\ORM\NoResultException;
use FOS\UserBundle\Model\UserManagerInterface;
use Map3\ProductBundle\Entity\Product;
use Map3\UserBundle\Entity\Role;
use Map3\UserBundle\Entity\User;
use Psr\Log\LoggerInterface;

/**
 * Set role for a user and a product.
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
class AbstractSetRoleService
{
    /**
     * @var EntityManager Entity manager
     */
    protected $entityManager;

    /**
     * @var UserManagerInterface User manager
     */
    protected $userManager;

    /**
     * @var LoggerInterface Logger
     */
    protected $logger;

    /**
     * @var User User entity
     */
    protected $user;

    /**
     * Set the role for the product.
     *
     * @param Product $product The product
     */
    public function setUserRole4Product(Product $product)
    {
        $repository = $this->entityManager->getRepository(
            'Map3UserBundle:UserPdtRole'
        );

        try {
            $userPdtRole = $repository->findByUserIdProductId(
                $this->user->getId(),
                $product->getId()
            );
            $roleId = $userPdtRole->getRole()->getId();
            $this->logger->debug('Role : '.$roleId);
            $this->user->addRole($roleId);

            $this->user->setCurrentRoleLabel(
                $userPdtRole->getRole()->getLabel()
            );
        } catch (NoResultException $e) {
            // Public product by default Guest role added.
            $this->logger->debug('Role by default: Guest');
            $this->user->addRole(Role::GUEST_ROLE);
        }
    }
}
