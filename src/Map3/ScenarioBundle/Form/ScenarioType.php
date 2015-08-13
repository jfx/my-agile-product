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
namespace Map3\ScenarioBundle\Form;

use Map3\CoreBundle\Form\AbstractDefaultType;
use Map3\ScenarioBundle\Entity\StatusRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Scenario form class.
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
class ScenarioType extends AbstractDefaultType
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
                'title',
                'text',
                array()
            )
            ->add(
                'extId',
                'text',
                array(
                    'horizontal_input_wrapper_class' => 'col-xs-4',
                    'render_required_asterisk' => false,
                )
            )
            ->add(
                'status',
                'entity',
                array(
                    'label' => 'Status',
                    'horizontal_input_wrapper_class' => 'col-xs-4',
                    'class' => 'Map3\ScenarioBundle\Entity\Status',
                    'property' => 'label',
                    'query_builder' => function (StatusRepository $sr) {
                        return $sr->getQBAllOrdered();
                    },
                )
            )
            ->add(
                'steps',
                'hidden',
                array()
            );
    }

    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $resolver The resolver for the options.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            $this->setDisabledAttr(
                array('data_class' => 'Map3\ScenarioBundle\Entity\Scenario')
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
        return 'map3_scenario';
    }
}
