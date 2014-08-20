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

namespace Map3\ReleaseBundle\Service;

use Doctrine\ORM\EntityManager;
use Map3\ReleaseBundle\Entity\Release;

/**
 * Product info service class.
 *
 * @category  MyAgileProduct
 * @package   Release
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class ReleaseInfo
{
    /**
     * @var EntityManager Entity manager
     */
    protected $entityManager;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager The entity manager.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager   = $entityManager;
    }

    /**
     * Get product info.
     *
     * @param Release $release The release.
     *
     * @return array
     */
    public function getChildCount($release)
    {
        $results = array();

        $repositoryBln = $this->entityManager->getRepository(
            'Map3BaselineBundle:Baseline'
        );

        $results['baselines']  = $repositoryBln->countBaselinesByRelease(
            $release
        );

        return $results;
    }
}
