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

use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * Role entity repository class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class RoleRepository extends SortableRepository
{
    /**
     * Get the default role (user).
     *
     * @return Role.
     */
    public function findDefaultRole()
    {
        return $this->find(ROLE::DEFAULT_ROLE);
    }

    /**
     * Get the query builder of all roles ordered.
     *
     * @return QueryBuilder.
     */
    public function getQBAllOrdered()
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.position', 'ASC');

        return $qb;
    }
}
