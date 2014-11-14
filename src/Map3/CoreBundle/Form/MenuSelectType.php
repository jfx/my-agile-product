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

namespace Map3\CoreBundle\Form;

use Map3\UserBundle\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Menu select form class.
 *
 * @category  MyAgileProduct
 * @package   Core
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class MenuSelectType extends DefaultType
{
    /**
     * @var User User object
     */
    protected $user;

    /**
     * Constructor
     *
     * @param User $user The user.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritDoc}
     *
     * @param FormBuilderInterface $builder Form builder.
     * @param array                $options Array of options.
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentBaseline = $this->user->getCurrentBaseline();
        if ($currentBaseline === null) {
            $currentBaselineId = 0;
        } else {
            $currentBaselineId = $currentBaseline->getId();
        }
        $availableBaselines = $this->user->getAvailableBaselines();

        if (count($availableBaselines) > 0) {
            $keyExists = false;
            foreach ($availableBaselines as $releases4aProduct) {
                if (array_key_exists($currentBaselineId, $releases4aProduct)) {
                    $keyExists = true;
                }
            }
            if ($keyExists) {
                $builder->add(
                    'search',
                    'choice',
                    array(
                        'label' => false,
                        'choices' => $availableBaselines,
                        'data' => $currentBaselineId,
                        'horizontal_input_wrapper_class' => 'col-lg-12',
                        'attr' => array(
                            'onChange' => "this.form.submit()",
                        ),
                    )
                );
            } else {
                $builder->add(
                    'search',
                    'choice',
                    array(
                        'label' => false,
                        'choices' => $availableBaselines,
                        'empty_value' => '',
                        'horizontal_input_wrapper_class' => 'col-lg-12',
                        'attr' => array(
                            'onChange' => "this.form.submit()",
                        ),
                    )
                );
            }
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return string Name of form.
     */
    public function getName()
    {
        return 'map3_select';
    }

    /**
     * {@inheritDoc}
     *
     * @return string route name.
     */
    public function getRoute()
    {
        return "map3_core_select";
    }
}
