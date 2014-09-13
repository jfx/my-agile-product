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

namespace Map3\BaselineBundle\Service;

use Map3\CoreBundle\Service\AbstractInfo;
use Map3\ReleaseBundle\Entity\Baseline;

/**
 * Baseline info service class.
 *
 * @category  MyAgileProduct
 * @package   Baseline
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class BaselineInfo extends AbstractInfo
{
    /**
     * Get baseline info.
     *
     * @param Baseline $baseline The baseline.
     *
     * @return array
     */
    public function getChildCount($baseline)
    {
        $results = array();

        $repositoryRef = $this->entityManager->getRepository(
            'Map3BaselineBundle:Reference'
        );

        $results['references']  = $repositoryRef->countReferencesByBaseline(
            $baseline
        );

        return $results;
    }
}
