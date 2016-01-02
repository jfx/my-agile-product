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

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Map3\ReleaseBundle\Entity\Release;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Baseline entity class.
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
 * @ORM\Table(name="map3_baseline")
 * @ORM\Entity(repositoryClass="Map3\BaselineBundle\Entity\BaselineRepository")
 */
class Baseline
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
     * @Assert\NotBlank()
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
     * @var DateTime Baseline date
     *
     * @ORM\Column(name="baselinedatetime", type="datetime")
     * @Assert\NotBlank(message="This value is not a valid datetime")
     * @Assert\Date()
     */
    protected $baselineDatetime;

    /**
     * @var bool Release closed or not.
     *
     * @ORM\Column(name="closed", type="boolean")
     */
    protected $closed;

    /**
     * @var Release Release
     *
     * @ORM\ManyToOne(targetEntity="Map3\ReleaseBundle\Entity\Release")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $release;

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
     * @return Baseline
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
     * @return Baseline
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
     * Set datetime of baseline.
     *
     * @param DateTime $baselineDatetime baseline datetime.
     *
     * @return Baseline
     */
    public function setBaselineDatetime($baselineDatetime)
    {
        $this->baselineDatetime = $baselineDatetime;

        return $this;
    }

    /**
     * Get datetime of baseline.
     *
     * @return DateTime
     */
    public function getBaselineDatetime()
    {
        return $this->baselineDatetime;
    }

    /**
     * Is release closed or not.
     *
     * @return bool
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * Set closed status.
     *
     * @param bool $closed Closed status.
     *
     * @return Baseline
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Set release.
     *
     * @param Release $rls The release
     *
     * @return Baseline
     */
    public function setRelease(Release $rls)
    {
        $this->release = $rls;

        return $this;
    }

    /**
     * Get Release.
     *
     * @return Release
     */
    public function getRelease()
    {
        return $this->release;
    }
}
