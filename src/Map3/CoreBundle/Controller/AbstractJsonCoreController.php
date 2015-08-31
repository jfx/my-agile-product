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
namespace Map3\CoreBundle\Controller;

use DomainException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Json Core controller class.
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
abstract class AbstractJsonCoreController extends AbstractCoreController
{
    const BASELINE = 'BAS';
    const CATEGORY = 'CAT';
    const FEATURE = 'FEAT';
    const SCENARIO = 'SCE';
    const TEST = 'TES';

    /**
     * Convert a html response to Json.
     *
     * @param Response $response The html response
     *
     * @return JsonResponse Response in Json format
     */
    protected function html2jsonResponse(Response $response)
    {
        $statusCode = $response->getStatusCode();
        $statusTexts = Response::$statusTexts;

        if (array_key_exists($statusCode, $statusTexts)) {
            $statusText = $statusTexts[$statusCode];
        } else {
            $statusText = '';
        }

        if ($statusCode == 200) {
            $content = $response->getContent();
        } else {
            $content = '';
        }

        return $this->jsonResponseFactory($statusCode, $statusText, $content);
    }

    /**
     * Create a Json response.
     *
     * @param int    $code    The status code
     * @param string $message The status text or message for the status code
     * @param string $content The content
     *
     * @return JsonResponse Response in Json format
     */
    protected function jsonResponseFactory($code, $message, $content = '')
    {
        return new JsonResponse(
            array(
                'message' => $message,
                'content' => $content,
            ),
            $code
        );
    }

    /**
     * Get object from jstree id.
     *
     * @param string $nodeId Id from jstree
     *
     * @return mixed
     */
    protected function getObjectFromNodeId($nodeId)
    {
        $node = $this->getIdFromNodeId($nodeId);
        $manager = $this->getDoctrine()->getManager();

        switch ($node['type']) {
            case self::BASELINE:
                $blnR = $manager->getRepository('Map3BaselineBundle:Baseline');
                $object = $blnR->find($node['id']);
                break;

            case self::CATEGORY:
                $catR = $manager->getRepository('Map3FeatureBundle:Category');
                $object = $catR->find($node['id']);
                break;

            case self::FEATURE:
                $featR = $manager->getRepository('Map3FeatureBundle:Feature');
                $object = $featR->find($node['id']);
                break;

            case self::SCENARIO:
                $sceR = $manager->getRepository('Map3ScenarioBundle:Scenario');
                $object = $sceR->find($node['id']);
                break;

            case self::TEST:
                $tesR = $manager->getRepository('Map3ScenarioBundle:Test');
                $object = $tesR->find($node['id']);
                break;
            
            default:
                throw new DomainException('Wrong type of node');
        }
        if (is_null($object)) {
            throw new DomainException('Not Found');
        }

        return $object;
    }
    /**
     * Get node Id and type from jstree id.
     *
     * @param string $nodeId Id from jstree
     *
     * @return array
     */
    protected function getIdFromNodeId($nodeId)
    {
        if (strpos($nodeId, ',') !== false) {
            throw new DomainException('Please, select only one node !');
        }
        $typeNodeIdExplode = explode('_', urldecode($nodeId));
        $array = array();

        switch ($typeNodeIdExplode[0]) {
            case 'B':
                $array['type'] = self::BASELINE;
                break;
            case 'C':
                $array['type'] = self::CATEGORY;
                break;
            case 'F':
                $array['type'] = self::FEATURE;
                break;
            case 'S':
                $array['type'] = self::SCENARIO;
                break;
            case 'T':
                $array['type'] = self::TEST;
                break;
            default:
                throw new DomainException('Wrong type of node');
        }
        if (isset($typeNodeIdExplode[1]) && is_numeric($typeNodeIdExplode[1])) {
            $array['id'] = $typeNodeIdExplode[1];
        } else {
            throw new DomainException('Wrong node Id');
        }

        return $array;
    }
}
