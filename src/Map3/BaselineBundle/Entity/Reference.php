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

namespace Map3\BaselineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reference entity class.
 *
 * @category  MyAgileProduct
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 * @ORM\Table(name="map3_reference")
 * @ORM\Entity(repositoryClass="Map3\BaselineBundle\Entity\ReferenceRepository")
 */
class Reference
{
    /**
     * @var int Id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string Label
     *
     * @ORM\Column(name="label", type="string", length=50)
     * @Assert\Length(min=2, max=50)
     */
    protected $label;

    /**
     * @var string Value
     *
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    protected $value;

    /**
     * @var Baseline Baseline
     *
     * @ORM\ManyToOne(targetEntity="Map3\BaselineBundle\Entity\Baseline")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $baseline;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label.
     *
     * @param string $label Label of reference.
     *
     * @return Reference
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set Value.
     *
     * @param string $value Value of reference
     *
     * @return Reference
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get Value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set baseline.
     *
     * @param Baseline $bln The baseline
     *
     * @return Reference
     */
    public function setBaseline(Baseline $bln)
    {
        $this->baseline = $bln;

        return $this;
    }

    /**
     * Get Baseline.
     *
     * @return Baseline
     */
    public function getBaseline()
    {
        return $this->baseline;
    }
}
