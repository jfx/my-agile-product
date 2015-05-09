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

namespace Map3\FeatureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Map3\BaselineBundle\Entity\Baseline;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Feature category entity class.
 *
 * @category  MyAgileProduct
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2015 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="map3_category")
 * @ORM\Entity(repositoryClass="Map3\FeatureBundle\Entity\CategoryRepository")
 */
class Category
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
     * @var Baseline Baseline
     *
     * @ORM\ManyToOne(targetEntity="Map3\BaselineBundle\Entity\Baseline")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $baseline;

    /**
     * @var int Left value
     *
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @var int Level
     *
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @var int Right value
     *
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @var int Id of root node
     *
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @var Category Parent node
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var array List children
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

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
     * Set name.
     *
     * @param string $name Name of release.
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set details.
     *
     * @param string $details Details
     *
     * @return Category
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details.
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set baseline.
     *
     * @param Baseline $bln The baseline
     *
     * @return Category
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

    /**
     * Set parent.
     *
     * @param Category $parent Parent node
     *
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent node.
     *
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }
}
