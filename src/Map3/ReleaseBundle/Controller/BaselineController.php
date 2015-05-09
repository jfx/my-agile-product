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

namespace Map3\ReleaseBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Controller\AbstractCoreController;
use Map3\ReleaseBundle\Entity\Release;
use Map3\UserBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baseline controller class.
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
class BaselineController extends AbstractCoreController
{
    /**
     * List of baselines.
     *
     * @param Release $release The release
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction(Release $release)
    {
        $this->setCurrentRelease($release, array(Role::GUEST_ROLE));

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3BaselineBundle:Baseline');

        $baselines = $repository->findBaselinesByRelease($release);

        return $this->render(
            'Map3ReleaseBundle:Baseline:index.html.twig',
            array(
                'baselines' => $baselines,
                'release' => $release,
            )
        );
    }
}
