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

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Map3\BaselineBundle\Entity\Baseline;
use Map3\ProductBundle\Entity\Product;
use Map3\ReleaseBundle\Entity\Release;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User entity class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 * @ORM\Entity
 * @ORM\Table(name="map3_user")
 * @ORM\Entity(repositoryClass="Map3\UserBundle\Entity\UserRepository")
 * @UniqueEntity(fields="displayname", message="A user with this displayname already exists.")
 * @UniqueEntity(fields="username", message="A user with this username already exists.")
 * @UniqueEntity(fields="email", message="A user with this email already exists.")
 */
class User extends BaseUser
{
    /**
     * @var integer Id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
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
     * @var string Firstname
     *
     * @ORM\Column(name="firstname", type="string", length=50)
     * @Assert\Length(min=2, max=50)
     */
    protected $firstname;

    /**
     * @var string Displayname
     *
     * @ORM\Column(name="displayname", type="string", length=50, unique=true)
     * @Assert\Length(min=2, max=50)
     */
    protected $displayname;

    /**
     * @var string Details
     *
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**
     * @var Product Current product
     *
     * @ORM\ManyToOne(targetEntity="Map3\ProductBundle\Entity\Product")
     */
    private $currentProduct;

    /**
     * @var Release Current release
     *
     * @ORM\ManyToOne(targetEntity="Map3\ReleaseBundle\Entity\Release")
     */
    private $currentRelease;

    /**
     * @var Baseline Current baseline
     *
     * @ORM\ManyToOne(targetEntity="Map3\BaselineBundle\Entity\Baseline")
     */
    private $currentBaseline;

    /**
     * @var string Current role label
     *
     * @ORM\Column(name="current_role_label", type="text", length=25, nullable=true)
     */
    private $currentRoleLabel;

    /**
     * @var array Available releases for user
     *
     * @ORM\Column(name="available_releases", type="array")
     */
    private $availableBaselines;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->availableReleases = array();
    }

    /**
     * Set name
     *
     * @param string $name The name
     *
     * @return User
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
     * Set firstname
     *
     * @param string $firstname The firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set displayname
     *
     * @param string $displayname The displayname
     *
     * @return User
     */
    public function setDisplayname($displayname)
    {
        $this->displayname = $displayname;

        return $this;
    }

    /**
     * Get displayname
     *
     * @return string
     */
    public function getDisplayname()
    {
        return $this->displayname;
    }

    /**
     * Set details
     *
     * @param string $details The details
     *
     * @return User
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
     * Set current product
     *
     * @param Product $pdt The current product
     *
     * @return User
     */
    public function setCurrentProduct(Product $pdt)
    {
        $this->currentProduct = $pdt;

        return $this;
    }

    /**
     * Get current product
     *
     * @return Product
     */
    public function getCurrentProduct()
    {
        return $this->currentProduct;
    }

    /**
     * unset current product
     *
     * @return User
     */
    public function unsetCurrentProduct()
    {
        $this->unsetProductRole();
        $this->unsetCurrentRelease();
        $this->currentProduct = null;

        return $this;
    }

    /**
     * Set current release
     *
     * @param Release $rl The current release
     *
     * @return User
     */
    public function setCurrentRelease(Release $rl)
    {
        $this->currentRelease = $rl;

        return $this;
    }

    /**
     * Get current release
     *
     * @return Release
     */
    public function getCurrentRelease()
    {
        return $this->currentRelease;
    }

    /**
     * unset current release
     *
     * @return User
     */
    public function unsetCurrentRelease()
    {
        $this->unsetCurrentBaseline();
        $this->currentRelease = null;

        return $this;
    }

    /**
     * Set current baseline
     *
     * @param Baseline $bln The current baseline
     *
     * @return User
     */
    public function setCurrentBaseline(Baseline $bln)
    {
        $this->currentBaseline = $bln;

        return $this;
    }

    /**
     * Get current baseline
     *
     * @return Baseline
     */
    public function getCurrentBaseline()
    {
        return $this->currentBaseline;
    }

    /**
     * unset current baseline
     *
     * @return User
     */
    public function unsetCurrentBaseline()
    {
        $this->currentBaseline = null;

        return $this;
    }

    /**
     * Set current role label
     *
     * @param string $crl Label of the role.
     *
     * @return User
     */
    public function setCurrentRoleLabel($crl)
    {
        $this->currentRoleLabel = $crl;

        return $this;
    }

    /**
     * Get current role label
     *
     * @return string
     */
    public function getCurrentRoleLabel()
    {
        return $this->currentRoleLabel;
    }

    /**
     * Set available baselines
     *
     * @param array $baselines List of baselines
     *
     * @return User
     */
    public function setAvailableBaselines($baselines)
    {
        $this->availableBaselines = $baselines;

        return $this;
    }

    /**
     * Get available baselines
     *
     * @return array
     */
    public function getAvailableBaselines()
    {
        return $this->availableBaselines;
    }

    /**
     * unset product role
     *
     * @return User
     */
    public function unsetProductRole()
    {
        $this->currentRoleLabel = null;

        foreach ($this->getRoles() as $key => $role) {

            if (substr($role, 0, 7) == 'ROLE_DM') {
                $this->removeRole($role);
            }
        }

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password The new password
     *
     * @return User
     */
    public function setUpdatedPassword($password)
    {
        if (!empty($password)) {
            $this->setPlainPassword($password);
        }

        return $this;
    }

    /**
     * Get an empty password
     *
     * @return string
     */
    public function getUpdatedPassword()
    {
        return '';
    }

    /**
     * Get Name and firstname
     *
     * @return string
     */
    public function getNameFirstname()
    {
        return $this->getName().' '.$this->getFirstname();
    }
}
