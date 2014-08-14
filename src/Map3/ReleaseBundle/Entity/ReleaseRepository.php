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

namespace Map3\ReleaseBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Map3\ProductBundle\Entity\Product;
use Map3\UserBundle\Entity\User;

/**
 * Release entity repository class.
 *
 * @category  MyAgileProduct
 * @package   Release
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class ReleaseRepository extends EntityRepository
{
    /**
     * Get all releases for a product.
     *
     * @param Product $product The product.
     *
     * @return array List of products.
     */
    public function findReleasesByProduct(Product $product)
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.product', 'p')
            ->where('p.id = :productId')
            ->setParameter('productId', $product->getId())
            ->orderBy('r.releaseDate', 'ASC');

        $results = $qb->getQuery()->getResult();

        return $results;
    }

    /**
     * Count all releases for a product.
     *
     * @param Product $product The product.
     *
     * @return int List of releases.
     */
    public function countReleasesByProduct(Product $product)
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.product', 'p')
            ->select('count(r.id)')
            ->where('p.id = :productId')
            ->setParameter('productId', $product->getId());

        $count = $qb->getQuery()->getSingleScalarResult();

        return $count;
    }

    /**
     * Get all releases for a user as a resource.
     *
     * @param User $user The user.
     *
     * @return array List of releases.
     */
    public function findAvailableReleasesByUser(User $user)
    {
        $sql  = ' select r.id as r_id, r.name as r_name, p.name as p_name';
        $sql .= ' from map3_release r';
        $sql .= ' inner join map3_product p on r.product_id = p.id';
        $sql .= ' inner join map3_user_pdt_role upr on upr.product_id = p.id';
        $sql .= ' where r.closed = 0';
        $sql .= ' and upr.user_id = :userId';
        $sql .= ' and upr.role_id != \'ROLE_DM_NONE\'';
        $sql .= ' order by p.name, r.name';

        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare($sql);
        $userId = $user->getId();
        $stmt->bindParam('userId', $userId);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $return = array();

        foreach ($results as $row) {

            if (!array_key_exists($row['p_name'], $return)) {
                $return[$row['p_name']] = array();
            }
            $return[$row['p_name']][$row['r_id']] = $row['r_name'];
        }

        return $return;
    }
}
