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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Map3\ProductBundle\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Release entity class.
 *
 * @category  MyAgileProduct
 * @package   Release
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 * @ORM\Table(name="map3_release")
 * @ORM\Entity(repositoryClass="Map3\ReleaseBundle\Entity\ReleaseRepository")
 */
class Release
{
    /**
     * @var integer Id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string Name
     *
     * @ORM\Column(name="name", type="string", length=50)
     * @Assert\Length(min=2, max=50)
     */
    protected $name;

    /**
     * @var string Details
     *
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    protected $details;

    /**
     * @var DateTime release date
     *
     * @ORM\Column(name="releasedate", type="date")
     */
    protected $releaseDate;

    /**
     * @var boolean Release closed or not.
     *
     * @ORM\Column(name="closed", type="boolean")
     */
    protected $closed;

    /**
     * @var Product Product
     *
     * @ORM\ManyToOne(targetEntity="Map3\ProductBundle\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $product;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name Name of release.
     *
     * @return Release
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set details
     *
     * @param string $details Details
     *
     * @return Release
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set date of release.
     *
     * @param DateTime $releaseDate release date.
     *
     * @return Release
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * Get date of release.
     *
     * @return DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Is release closed or not.
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * Set closed status
     *
     * @param boolean $closed Closed status.
     *
     * @return Release
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Set product
     *
     * @param Product $pdt The product
     *
     * @return Release
     */
    public function setProduct(Product $pdt)
    {
        $this->product = $pdt;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
