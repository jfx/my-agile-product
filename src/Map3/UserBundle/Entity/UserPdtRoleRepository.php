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
namespace Map3\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Map3\ProductBundle\Entity\Product;

/**
 * User Product Role entity repository class.
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
class UserPdtRoleRepository extends EntityRepository
{
    /**
     * Get Entity name (parent method is protected).
     *
     * @return string
     */
    public function getPublicEntityName()
    {
        return $this->getEntityName();
    }

    /**
     * Get all resources with role for a product.
     *
     * @param Product $product The product
     *
     * @return array List of users and role
     */
    public function findUsersByProduct(Product $product)
    {
        $qb = $this->createQueryBuilder('upr')
            ->innerJoin('upr.user', 'u')
            ->select('u.id, u.name, u.firstname, u.displayname')
            ->innerJoin('upr.role', 'r')
            ->addSelect('r.label')
            ->innerJoin('upr.product', 'p')
            ->addSelect('p.name as product_name')
            ->where('p.id = :productId')
            ->setParameter('productId', $product->getId())
            ->orderBy('u.name, u.firstname', 'ASC');

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * Count all resources with role for a product.
     *
     * @param Product $product The product
     *
     * @return int
     */
    public function countUsersByProduct(Product $product)
    {
        $qb = $this->createQueryBuilder('upr')
            ->innerJoin('upr.user', 'u')
            ->select('count(u.id)')
            ->innerJoin('upr.product', 'p')
            ->where('p.id = :productId')
            ->setParameter('productId', $product->getId());

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }

    /**
     * Get all products for a user as a resource.
     *
     * @param User $user The user
     *
     * @return array List of products
     */
    public function findAvailableProductsByUser(User $user)
    {
        $qb = $this->createQueryBuilder('upr')
            ->innerJoin('upr.product', 'p')
            ->select('p.id, p.name')
            ->innerJoin('upr.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->innerJoin('upr.role', 'r')
            ->addSelect('r.label')
            ->andWhere('r.id != \'ROLE_DM_NONE\'')
            ->orderBy('p.name', 'ASC');

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * Get a role for a user and product.
     *
     * @param int $userId    The user id
     * @param int $productId The product id
     *
     * @return array roles
     */
    public function findByUserIdProductId($userId, $productId)
    {
        $qb = $this->createQueryBuilder('upr')
            ->join('upr.user', 'u')
            ->join('upr.product', 'p')
            ->where('u.id = :userId')
            ->andWhere('p.id = :productId')
            ->setParameter('userId', $userId)
            ->setParameter('productId', $productId);

        $result = $qb->getQuery()->getSingleResult();

        return $result;
    }

    /**
     * Get the query builder of all user for a product id.
     *
     * @param int $param The product id
     *
     * @return QueryBuilder
     */
    public function getQBUserIdByProductParam($param)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('userqb.id')
            ->from($this->_entityName, 'upr')
            ->join('upr.user', 'userqb')
            ->join('upr.product', 'productqb')
            ->where('productqb.id = :'.$param);

        return $qb;
    }
}
