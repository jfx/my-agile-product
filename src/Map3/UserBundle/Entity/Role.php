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

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Role entity class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 * @ORM\Table(name="map3_role")
 * @ORM\Entity(repositoryClass="Map3\UserBundle\Entity\RoleRepository")
 */
class Role
{
    const GUEST_ROLE   = 'ROLE_DM_GUEST';
    const DEFAULT_ROLE = 'ROLE_DM_USER';
    const MANAGER_ROLE = 'ROLE_DM_MANAGER';
    const LABEL_NONE   = 'None';

    /**
     * @var string Id
     *
     * @ORM\Column(name="id", type="string", length=20)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string Label
     *
     * @ORM\Column(name="label", type="string", length=20)
     */
    private $label;

    /**
     * @var integer Position
     *
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * Set id.
     *
     * @param string $id Id.
     *
     * @return Role
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label The label.
     *
     * @return Role
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set order
     *
     * @param integer $position The order.
     *
     * @return Role
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
}
