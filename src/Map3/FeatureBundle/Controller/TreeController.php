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

namespace Map3\FeatureBundle\Controller;

use \Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\BaselineBundle\Entity\Baseline;
use Map3\CoreBundle\Controller\AbstractCoreController;
use Map3\UserBundle\Entity\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Feature controller class.
 *
 * @category  MyAgileProduct
 * @package   Feature
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2015 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 */
class TreeController extends AbstractCoreController
{
    const CATEGORY = 'CAT';

    /**
     * Get children for a parent
     *
     * @param Baseline $baseline The baseline to view
     * @param Request  $request  The request
     *
     * @return Response A Response instance
     *
     * @ParamConverter("baseline", options={"mapping": {"bid": "id"}})
     * @Secure(roles="ROLE_USER")
     */
    public function childrenAction(Baseline $baseline, Request $request)
    {
        $this->setCurrentBaseline($baseline, array(Role::GUEST_ROLE));

        $treeId = $request->query->get('pid');

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('Map3FeatureBundle:Category');

        if ($treeId == '#') {
            $root = $repository->findRootByBaseline($baseline);

            return $this->render(
                'Map3FeatureBundle:Tree:root.json.twig',
                array(
                    'baseline' => $baseline,
                    'root' => $root,
                )
            );
        } else {
            try {
                $nodeId = $this->getIdFromTreeId($treeId)['id'];
            } catch (Exception $e) {
                return new JsonResponse(
                    array('message' => $e->getMessage()),
                    404
                );
            }
            $children = $repository->findChildrenByBaselineParentId(
                $baseline,
                $nodeId
            );

            return $this->render(
                'Map3FeatureBundle:Tree:children.json.twig',
                array(
                    'children' => $children,
                )
            );
        }
    }

    /**
     * Get node Id and type from jstree id
     *
     * @param string $typeNodeId Id from jstree
     *
     * @return array
     */
    protected function getIdFromTreeId($typeNodeId)
    {
        $typeNodeIdExplode = explode('_', urldecode($typeNodeId));

        switch ($typeNodeIdExplode[0]) {
            case 'C':
                $array['type'] = self::CATEGORY;
                break;
            default:
                throw new Exception('Wrong type of node');
        }
        if (isset($typeNodeIdExplode[1]) && is_numeric($typeNodeIdExplode[1])) {
            $array['id'] = $typeNodeIdExplode[1];
        } else {
            throw new Exception('Wrong node Id');
        }

        return $array;
    }
}
