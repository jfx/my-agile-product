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
use Map3\BaselineBundle\Entity\Baseline;
use Map3\ProductBundle\Entity\Product;
use Map3\ReleaseBundle\Entity\Release;
use Psr\Log\LoggerInterface;

/**
 * Remove context service class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class RemoveContextService
{
    /**
     * @var EntityManager Entity manager
     */
    protected $entityManager;

    /**
     * @var UserManagerInterface User manager
     */
    protected $userManager;

    /**
     * @var LoggerInterface Logger
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param EntityManager        $entityManager The entity manager
     * @param UserManagerInterface $userManager   The user manager
     * @param LoggerInterface      $logger        The logger
     */
    public function __construct(
        EntityManager $entityManager,
        UserManagerInterface $userManager,
        LoggerInterface $logger
    ) {
        $this->entityManager   = $entityManager;
        $this->userManager     = $userManager;
        $this->logger          = $logger;
    }

    /**
     * Remove a product from context of all users.
     *
     * @param Product $product The product to remove
     *
     * @return void
     */
    public function removeProduct(Product $product)
    {
        $this->logger->debug('RemoveContextService->removeProduct');

        $repository = $this->entityManager
            ->getRepository('Map3UserBundle:User');

        $users = $repository->findUsersByProductInContext($product);

        foreach ($users as $user) {
            $this->logger->debug('$user->unsetCurrentProduct');
            $user->unsetCurrentProduct();
            $this->userManager->updateUser($user);
        }
    }

    /**
     * Remove a release from context of all users.
     *
     * @param Release $release The release to remove
     *
     * @return void
     */
    public function removeRelease(Release $release)
    {
        $this->logger->debug('RemoveContextService->removeRelease');

        $repository = $this->entityManager
            ->getRepository('Map3UserBundle:User');

        $users = $repository->findUsersByReleaseInContext($release);

        foreach ($users as $user) {
            $this->logger->debug('$user->unsetCurrentRelease');
            $user->unsetCurrentRelease();
            $this->userManager->updateUser($user);
        }
    }

    /**
     * Remove a baseline from context of all users.
     *
     * @param Baseline $baseline The baseline to remove
     *
     * @return void
     */
    public function removeBaseline(Baseline $baseline)
    {
        $this->logger->debug('RemoveContextService->removeBaseline');

        $repository = $this->entityManager
            ->getRepository('Map3UserBundle:User');

        $users = $repository->findUsersByBaselineInContext($baseline);

        foreach ($users as $user) {
            $this->logger->debug('$user->unsetCurrentBaseline');
            $user->unsetCurrentBaseline();
            $this->userManager->updateUser($user);
        }
    }
}
