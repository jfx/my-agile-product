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

use JMS\SecurityExtraBundle\Annotation\Secure;
use Map3\CoreBundle\Form\MenuSelectType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Core controller class.
 *
 * @category  MyAgileProduct
 * @package   Core
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 *
 */
class SelectController extends Controller
{
    /**
     * Display html select object in menu.
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function displayAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(new MenuSelectType($user));

        return $this->render(
            'Map3CoreBundle:Select:display.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Select a release in combobox
     *
     * @param Request $request The request
     *
     * @return Response A Response instance
     *
     * @Secure(roles="ROLE_USER")
     */
    public function requestAction(Request $request)
    {
        $baselineId = $request->request->get('map3_select')['search'];

        return $this->redirect(
            $this->generateUrl('baseline_view', array('id' => $baselineId))
        );
    }
}
