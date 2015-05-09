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

namespace Map3\UserBundle\Tests\Service;

use Map3\UserBundle\Service\PasswordFactoryService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Password factory test class.
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
class PasswordFactoryServiceTest extends WebTestCase
{
    /**
     * @var PasswordFactoryService Password factory service
     */
    private $pfs;

    /**
     * @var int Default length
     */
    private $defaultLength;

    /**
     * Setup method.
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $container = static::$kernel->getContainer();

        $this->pfs = $container->get('map3_user.passwordFactoryService');

        $this->defaultLength = $container->getParameter(
            'app.defaultPasswordLength'
        );
    }

    /**
     * Test method.
     */
    public function testGeneratePasswordWithDefaultLenght()
    {
        $pass0 = $this->pfs->generatePassword();
        $this->assertEquals($this->defaultLength, strlen($pass0));
        $this->assertGreaterThan(
            $this->defaultLength / 2,
            count(array_filter(count_chars($pass0)))
        );

        $pass1 = $this->pfs->generatePassword();
        $this->assertEquals($this->defaultLength, strlen($pass1));
        $this->assertGreaterThan(
            $this->defaultLength / 2,
            count(array_filter(count_chars($pass1)))
        );
        $this->assertNotEquals($pass1, $pass0);
    }

    /**
     * Test method.
     */
    public function testGeneratePasswordWithDefinedLenght()
    {
        $pass0 = $this->pfs->generatePassword(16);
        $this->assertEquals(16, strlen($pass0));
        $this->assertGreaterThan(8, count(array_filter(count_chars($pass0))));

        $pass1 = $this->pfs->generatePassword(16);
        $this->assertEquals(16, strlen($pass1));
        $this->assertGreaterThan(8, count(array_filter(count_chars($pass1))));
        $this->assertNotEquals($pass1, $pass0);
    }
}
