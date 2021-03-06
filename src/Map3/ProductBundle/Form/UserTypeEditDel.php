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

namespace Map3\ProductBundle\Form;

use Map3\UserBundle\Entity\RoleRepository;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * User form class for edit and delete actions.
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
class UserTypeEditDel extends AbstractUserType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'userNameFirstname',
                'text',
                array(
                    'label' => 'User',
                    'disabled' => true,
                )
            )
            ->add(
                'role',
                'entity',
                array(
                    'label' => 'Role',
                    'class' => 'Map3\UserBundle\Entity\Role',
                    'property' => 'label',
                    'query_builder' => function (RoleRepository $er) {
                        return $er->getQBAllOrdered();
                    },
                )
            );
    }
}
