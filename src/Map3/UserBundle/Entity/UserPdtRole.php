<?php
/**
 * LICENSE : This file is part of My Agile Project.
 *
 * My Agile Project is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * My Agile Project is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Map3\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Map3\ProductBundle\Entity\Product;
use Map3\UserBundle\Entity\Role;
use Map3\UserBundle\Entity\User;

/**
 * UserPdtRole entity class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproject.org
 * @since     3
 *
 * @ORM\Table(name="map3_user_pdt_role")
 * @ORM\Entity(repositoryClass="Map3\UserBundle\Entity\UserPdtRoleRepository")
 */
class UserPdtRole
{

    /**
     * @var User User
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Map3\UserBundle\Entity\User")
     */
    protected $user;

    /**
     * @var Product Product
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Map3\ProductBundle\Entity\Product")
     */
    protected $product;

    /**
     * @var Role Role
     *
     * @ORM\ManyToOne(targetEntity="Map3\UserBundle\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $role;

    /**
     * Set User
     *
     * @param User $user The user
     *
     * @return UserPdtRole
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set Product
     *
     * @param Product $pdt The product
     *
     * @return UserPdtRole
     */
    public function setDomain(Product $pdt)
    {
        $this->product = $pdt;

        return $this;
    }

    /**
     * Get Product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set role
     *
     * @param Role $role The role
     *
     * @return UserPdtRole
     */
    public function setRole(Role $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get name and firstname of user
     *
     * @return string
     */
    public function getUserNameFirstname()
    {
        return $this->user->getNameFirstname();
    }
}
