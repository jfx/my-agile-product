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
use Doctrine\ORM\QueryBuilder;
use Map3\BaselineBundle\Entity\Baseline;
use Map3\ProductBundle\Entity\Product;
use Map3\ReleaseBundle\Entity\Release;

/**
 * User entity repository class.
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
class UserRepository extends EntityRepository
{
    /**
     * Get all users ordered by name, firstname.
     *
     * @return array List of users
     */
    public function findAllOrderByNameFirstname()
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->orderBy('u.name, u.firstname');

        $results = $queryBuilder->getQuery()->getResult();

        return $results;
    }

    /**
     * Get all users with a product in their context.
     *
     * @param Product $product The product
     *
     * @return User[] List of users and role
     */
    public function findUsersByProductInContext(Product $product)
    {
        $qb = $this->createQueryBuilder('u')
            ->innerJoin('u.currentProduct', 'p')
            ->where('p.id = :productId')
            ->setParameter('productId', $product->getId());

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * Get all users with a release in their context.
     *
     * @param Release $release The release
     *
     * @return User[] List of users
     */
    public function findUsersByReleaseInContext(Release $release)
    {
        $qb = $this->createQueryBuilder('u')
            ->innerJoin('u.currentRelease', 'r')
            ->where('r.id = :releaseId')
            ->setParameter('releaseId', $release->getId());

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * Get all users with a baseline in their context.
     *
     * @param Baseline $baseline The baseline
     *
     * @return User[] List of users
     */
    public function findUsersByBaselineInContext(Baseline $baseline)
    {
        $qb = $this->createQueryBuilder('u')
            ->innerJoin('u.currentBaseline', 'b')
            ->where('b.id = :baselineId')
            ->setParameter('baselineId', $baseline->getId());

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * Get count of all available user for a product.
     *
     * @param Product $product The product
     *
     * @return int
     */
    public function getCountAvailableUserByProduct(Product $product)
    {
        $subQuery = $this->getDQLAddedUserByProduct();

        $qb = $this->createQueryBuilder('u');
        $qb->select('count(u.id)')
            ->where($qb->expr()->notIn('u.id', $subQuery))
            ->andWhere('u.locked = false')
            ->setParameter('productId', $product->getId());

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }

    /**
     * Get the query builder of all available user for a product.
     *
     * @param Product $product The product
     *
     * @return QueryBuilder
     */
    public function getQBAvailableUserByProduct(Product $product)
    {
        $subQuery = $this->getDQLAddedUserByProduct();

        $qb = $this->createQueryBuilder('u');
        $qb->where($qb->expr()->notIn('u.id', $subQuery))
            ->andWhere('u.locked = false')
            ->orderBy('u.name, u.firstname', 'ASC')
            ->setParameter('productId', $product->getId());

        return $qb;
    }

    /**
     * Get the query builder of all user for a product id.
     *
     * @param Product $product The product
     *
     * @return QueryBuilder
     */
    public function getQBAllUserWithActiveRoleByProduct(Product $product)
    {
        $uprRepository = $this->getUserPdtRoleRepository();
        $uprEntityName = $uprRepository->getPublicEntityName();

        $qb = $this->createQueryBuilder('u')
            ->from($uprEntityName, 'upr')
            ->join('upr.product', 'p')
            ->where('p.id = :productId')
            ->andWhere('upr.user = u')
            ->orderBy('u.name, u.firstname', 'ASC')
            ->setParameter('productId', $product->getId());

        return $qb;
    }

    /**
     * Get the DQL query of all added user for a product.
     *
     * @return DQL query
     */
    private function getDQLAddedUserByProduct()
    {
        $uprRepository = $this->getUserPdtRoleRepository();

        $subQuery = $uprRepository
            ->getQBUserIdByProductParam('productId')
            ->getDQL();

        return $subQuery;
    }

    /**
     * Get UserPdtRole repository.
     *
     * @return UserPdtRoleRepository
     */
    private function getUserPdtRoleRepository()
    {
        $uprRepository = $this->_em->getRepository(
            'Map3UserBundle:UserPdtRole'
        );

        return $uprRepository;
    }
}
