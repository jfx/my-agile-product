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
namespace Map3\ScenarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Map3\BaselineBundle\Entity\Baseline;
use Map3\FeatureBundle\Entity\Feature;
use Map3\ScenarioBundle\Util\Parser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Scenario entity class.
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
 * @ORM\Table(name="map3_scenario")
 * @ORM\Entity(repositoryClass="Map3\ScenarioBundle\Entity\ScenarioRepository")
 */
class Scenario
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
     * @var string Title
     *
     * @ORM\Column(name="title", type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    protected $title;

    /**
     * @var string External Id
     *
     * @ORM\Column(name="extid", type="string", length=50, nullable=true)
     * @Assert\Length(min=0, max=50)
     */
    protected $extId;

    /**
     * @var Status Status
     *
     * @ORM\ManyToOne(targetEntity="Map3\ScenarioBundle\Entity\Status")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $status;

    /**
     * @var string Steps
     *
     * @ORM\Column(name="steps", type="text", nullable=true)
     */
    protected $steps;

    /**
     * @var Baseline Baseline
     *
     * @ORM\ManyToOne(targetEntity="Map3\BaselineBundle\Entity\Baseline")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $baseline;

    /**
     * @var Feature Feature
     *
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Map3\FeatureBundle\Entity\Feature")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $feature;

    /**
     * @var int Position
     *
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

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
     * Get tree node id.
     *
     * @return string
     */
    public function getNodeId()
    {
        return 'S_'.$this->id;
    }

    /**
     * Set title.
     *
     * @param string $title Title of the feature
     *
     * @return Scenario
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get External id and title.
     *
     * @return string
     */
    public function getExtIdTitle()
    {
        if ((!is_null($this->extId)) && (strlen($this->extId) > 0)) {
            return $this->extId.' - '.$this->title;
        } else {
            return $this->title;
        }
    }

    /**
     * Set external Id.
     *
     * @param string $extId external Id of the feature
     *
     * @return Scenario
     */
    public function setExtId($extId)
    {
        $this->extId = $extId;

        return $this;
    }

    /**
     * Get external Id.
     *
     * @return string
     */
    public function getExtId()
    {
        return $this->extId;
    }

    /**
     * Set status.
     *
     * @param Status $status The status
     *
     * @return Scenario
     */
    public function setStatus(Status $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get Status.
     *
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set steps.
     *
     * @param string $steps Steps list
     *
     * @return Scenario
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * Get steps.
     *
     * @return string
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * Get steps in an array.
     *
     * @return array
     */
    public function getFormatedArraySteps()
    {
        $parser = new Parser($this->getSteps());
        $parser->parse();

        return $parser->getFormatedSteps();
    }

    /**
     * Get steps count.
     *
     * @return int
     */
    public function getStepsCount()
    {
        return count($this->getFormatedArraySteps());
    }

    /**
     * Set baseline.
     *
     * @param Baseline $bln The baseline
     *
     * @return Scenario
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
     * Set feature.
     *
     * @param Feature $feature The feature
     *
     * @return Scenario
     */
    public function setFeature(Feature $feature)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature.
     *
     * @return Feature
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * Set order.
     *
     * @param int $position The order.
     *
     * @return Scenario
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}
