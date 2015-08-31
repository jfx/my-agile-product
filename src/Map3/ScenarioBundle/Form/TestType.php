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
use Map3\ProductBundle\Entity\Product;
use Map3\UserBundle\Entity\UserRepository;
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
class TestType extends AbstractDefaultType
{
    /**
     * @var Product Product object
     */
    protected $product;
    
    /**
     * @var array Available results list
     */
    protected $availableResults;
    
    /**
     * Constructor.
     *
     * @param Product $product          The product.
     * @param array   $availableResults Available results.
     */
    public function __construct(Product $product, array $availableResults)
    {
        $this->product          = $product;
        $this->availableResults = $availableResults;
    }
    
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
                'testDatetime',
                'datetime',
                array(
                    'label' => 'Date',
                    'widget' => 'single_text',
                    'datetimepicker' => true,
                    'horizontal_input_wrapper_class' => 'col-lg-5',
                )
            )
            ->add(
                'tester',
                'entity',
                array(
                    'label' => 'Tester',
                    'horizontal_input_wrapper_class' => 'col-xs-4',
                    'class' => 'Map3\UserBundle\Entity\User',
                    'property' => 'nameFirstname',
                    'query_builder' => function (UserRepository $repo) {
                        return $repo->getQBAllUserWithActiveRoleByProduct(
                            $this->product);
                    },
                )
            )
            ->add(
                'stepsResults',
                'collection',
                array(
                    'type'   => 'choice',
                    'horizontal_input_wrapper_class' => 'col-xs-4',
                    'options'  => array(
                        'choices'  => $this->availableResults,
                    ),
                )
            )
            ->add(
                'comment',
                'textarea',
                array(
                    'required' => false,
                    'attr' => array(
                        'rows' => 4,
                    ),
                )
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
                array('data_class' => 'Map3\ScenarioBundle\Entity\Test')
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
        return 'map3_test';
    }
}
