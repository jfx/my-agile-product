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

use Exception;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\BaselineBundle\Entity\Baseline;
use Map3\CoreBundle\Controller\AbstractJsonCoreController;
use Map3\UserBundle\Entity\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Feature controller class.
 *
 * @category  MyAgileProduct
 *
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2015 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 *
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class TreeController extends AbstractJsonCoreController
{
    /**
     * Get children for a parent.
     *
     * @param Baseline $baseline The baseline
     * @param Request  $request  The request
     *
     * @return Response A Response instance
     *
     * @ParamConverter("baseline", options={"mapping": {"bid": "id"}})
     * @Secure(roles="ROLE_USER")
     */
    public function childAction(Baseline $baseline, Request $request)
    {
        $treeId = $request->query->get('pid');

        if ($treeId == '#') {
            $this->setCurrentBaseline($baseline, array(Role::GUEST_ROLE));

            $repository = $this->getDoctrine()
                ->getManager()
                ->getRepository('Map3FeatureBundle:Category');
            $root = $repository->findRootByBaseline($baseline);

            return $this->render(
                'Map3FeatureBundle:Tree:root.json.twig',
                array(
                    'baseline' => $baseline,
                    'root' => $root,
                )
            );
        } else {
            // Security delagated to forward request
            try {
                $idType = $this->getIdFromNodeId($treeId);
            } catch (Exception $e) {
                return $this->jsonResponseFactory(404, $e->getMessage());
            }
            switch ($idType['type']) {
                case self::BASELINE:
                case self::CATEGORY:
                    $response = $this->forward(
                        'Map3FeatureBundle:Category:child',
                        array('id' => $idType['id'])
                    );

                    return $response;
                    
                case self::FEATURE:
                    $response = $this->forward(
                        'Map3FeatureBundle:Feature:child',
                        array('id' => $idType['id'])
                    );

                    return $response;
                default:
                    return $this->jsonResponseFactory(
                        404,
                        'Wrong type of node'
                    );
            }
        }
    }

    /**
     * Display node details on right panel.
     *
     * @param int $bid The baseline id
     * @param int $nid The node id
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function nodeAction($bid, $nid)
    {
        // Security delagated to forward request
        try {
            $idType = $this->getIdFromNodeId($nid);
        } catch (Exception $e) {
            return $this->jsonResponseFactory(404, $e->getMessage());
        }
        switch ($idType['type']) {
            case self::BASELINE:
                $response = $this->forward(
                    'Map3BaselineBundle:Baseline:node',
                    array('id' => $bid)
                );
                break;
            case self::CATEGORY:
                $response = $this->forward(
                    'Map3FeatureBundle:Category:view',
                    array('id' => $idType['id'])
                );
                break;
            case self::FEATURE:
                $response = $this->forward(
                    'Map3FeatureBundle:Feature:view',
                    array('id' => $idType['id'])
                );
                break;
            case self::SCENARIO:
                $response = $this->forward(
                    'Map3ScenarioBundle:Scenario:view',
                    array('id' => $idType['id'])
                );
                break;
            default:
                return $this->jsonResponseFactory(404, 'Wrong type of node');
        }

        return $this->html2jsonResponse($response);
    }

    /**
     * Remove a node.
     *
     * @param int $bid The baseline id
     * @param int $nid The node id
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function delAction($bid, $nid)
    {
        // Security delagated to forward request
        try {
            $idType = $this->getIdFromNodeId($nid);
        } catch (Exception $e) {
            return $this->jsonResponseFactory(404, $e->getMessage());
        }
        switch ($idType['type']) {
            case self::BASELINE:
                return $this->jsonResponseFactory(405, 'Operation not allowed');
            case self::CATEGORY:
                $response = $this->forward(
                    'Map3FeatureBundle:Category:del',
                    array('id' => $idType['id'])
                );
                break;
            case self::FEATURE:
                $response = $this->forward(
                    'Map3FeatureBundle:Feature:del',
                    array('id' => $idType['id'])
                );
                break;
            default:
                return $this->jsonResponseFactory(404, 'Wrong type of node');
        }

        return $this->html2jsonResponse($response);
    }
}
