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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Json Core controller class.
 *
 * @category  MyAgileProduct
 * @package   Core
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2015 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 */
abstract class AbstractJsonCoreController extends AbstractCoreController
{
    /**
     * Convert a html response to Json
     *
     * @param Response $response The html response
     *
     * @return JsonResponse Response in Json format
     */
    protected function html2jsonResponse(Response $response)
    {
        $statusCode  = $response->getStatusCode();
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
     * Create a Json response
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
}
