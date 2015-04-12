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

namespace Map3\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Map3\ProductBundle\Entity\Product;
use Map3\ReleaseBundle\Entity\Release;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Update context service class.
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
class UpdateContextService extends AbstractSetRoleService
{
    /**
     * @var TokenStorageInterface Token storage interface
     */
    protected $tokenStorage;

    /**
     * @var bool userHasChanged Has User changed ?
     */
    protected $userHasChanged = false;

    /**
     * Constructor.
     *
     * @param TokenStorageInterface $tokenStorage  The token storage
     * @param EntityManager         $entityManager The entity manager
     * @param UserManagerInterface  $userManager   The user manager
     * @param LoggerInterface       $logger        The logger
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EntityManager $entityManager,
        UserManagerInterface $userManager,
        LoggerInterface $logger
    ) {
        $this->tokenStorage  = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->userManager   = $userManager;
        $this->logger        = $logger;

        $this->user = $this->tokenStorage->getToken()->getUser();
    }

    /**
     * Set the current product for a user and set role.
     *
     * @param Product|null $product The product, if null unset product.
     */
    public function setCurrentProduct($product)
    {
        $this->logger->debug('Init Ctx4U->setCurrentProduct');

        $this->setCurrentProductChilds($product, true);

        $this->updateUserIfChanged();
    }

    /**
     * Set the current release and set product and role.
     *
     * @param Release|null $release The release, if null unset current release
     */
    public function setCurrentRelease($release)
    {
        $this->logger->debug('Init Ctx4U->setCurrentRelease');

        $this->setCurrentReleaseChilds($release, true);

        $this->updateUserIfChanged();
    }

    /**
     * Set the current baseline and set release/product and role.
     *
     * @param Baseline|null $baseline The baseline, if null unset current
     *                                baseline.
     */
    public function setCurrentBaseline($baseline)
    {
        $this->logger->debug('Init Ctx4U->setCurrentBaseline');

        $this->setCurrentBaselineChilds($baseline);

        $this->updateUserIfChanged();
    }

    /**
     * Update user if context of user has changed.
     */
    public function updateUserIfChanged()
    {
        if ($this->userHasChanged) {
            $this->logger->debug('Update user');
            $this->tokenStorage->getToken()->setAuthenticated(false);
            $this->userManager->updateUser($this->user);
        }
    }

    /**
     * Set the current product for a user and set role.
     *
     * @param Product|null $product     The product, if null unset product.
     * @param bool         $resetChilds Current childs must be resetted.
     */
    private function setCurrentProductChilds($product, $resetChilds = true)
    {
        if ($resetChilds) {
            $this->logger->debug('Reset childs : Release and above');
            $this->userHasChanged = true;
            $this->user->unsetCurrentRelease();
        }

        if ($product === null) {
            $this->logger->debug('User->unsetCurrentProduct');
            $this->userHasChanged = true;
            $this->user->unsetCurrentProduct();
        } else {
            $currentProduct = $this->user->getCurrentProduct();

            if ($product != $currentProduct) {
                $this->logger->debug('User->setCurrentProduct');

                $this->userHasChanged = true;
                $this->user->unsetProductRole();
                $this->user->setCurrentProduct($product);

                $this->setUserRole4Product($product);
            } else {
                $this->logger->debug('Same product. No change');
            }
        }
    }

    /**
     * Set the current release and set product and role.
     *
     * @param Release|null $release     The release, if null unset release
     * @param bool         $resetChilds Current childs must be resetted
     */
    private function setCurrentReleaseChilds($release, $resetChilds = true)
    {
        if ($resetChilds) {
            $this->logger->debug('Reset childs : Baseline and above');
            $this->userHasChanged = true;
            $this->user->unsetCurrentBaseline();
        }

        if ($release === null) {
            $this->logger->debug('User->unsetCurrentRelease');
            $this->userHasChanged = true;
            $this->user->unsetCurrentRelease();
        } else {
            $currentRelease = $this->user->getCurrentRelease();

            if ($release != $currentRelease) {
                $this->logger->debug('User->setCurrentRelease');
                $this->user->setCurrentRelease($release);

                $this->userHasChanged = true;

                $product = $release->getProduct();
                $this->setCurrentProductChilds($product, false);
            } else {
                $this->logger->debug('Same release. No change');
            }
        }
    }

    /**
     * Set the current release and set product and role.
     *
     * @param Baseline|null $baseline The baseline, if null unset current
     *                                baseline.
     */
    private function setCurrentBaselineChilds($baseline)
    {
        if ($baseline === null) {
            $this->logger->debug('User->unsetCurrentBaseline');
            $this->userHasChanged = true;
            $this->user->unsetCurrentBaseline();
        } else {
            $currentBaseline = $this->user->getCurrentBaseline();

            if ($baseline != $currentBaseline) {
                $this->logger->debug('User->setCurrentBaseline');
                $this->user->setCurrentBaseline($baseline);

                $this->userHasChanged = true;

                $release = $baseline->getRelease();
                $this->setCurrentReleaseChilds($release, false);
            } else {
                $this->logger->debug('Same baseline. No change');
            }
        }
    }
}
