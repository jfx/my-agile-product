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

/**
 * Release entity repository class.
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
class ReleaseRepository extends EntityRepository
{
    /**
     * Get all releases for a product.
     *
     * @param Product $product The product.
     *
     * @return array List of relases.
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
     * @return int count of releases.
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
}
