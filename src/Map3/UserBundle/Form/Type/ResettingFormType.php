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

namespace Map3\UserBundle\Form\Type;

use FOS\UserBundle\Form\Type\ResettingFormType as FOS_ResettingFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Resetting password form class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class ResettingFormType extends FOS_ResettingFormType
{
    /**
     * Builds the form view.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the view.
     *
     * A view of a form is built before the views of the child forms are built.
     * This means that you cannot access child views in this method. If you need
     * to do so, move your logic to {@link finishView()} instead.
     *
     * @param FormView      $view    The view
     * @param FormInterface $form    The form
     * @param array         $options The options
     *
     * @return void
     */
    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ) {
        $view->vars = array_replace(
            $view->vars,
            array(
                'render_fieldset' => false,
                'show_legend'     => false,
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "map3_resettingformtype";
    }
}
