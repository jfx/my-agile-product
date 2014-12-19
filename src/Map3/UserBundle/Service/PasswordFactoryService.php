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

namespace Map3\UserBundle\Service;

/**
 * Generate random password.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class PasswordFactoryService
{
    /**
     * @var int Default length of generated password.
     *
     */
    protected $defaultLength;

    /**
     * @var string Available characters for password.
     *
     */
    protected static $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";

    /**
     * Constructor
     *
     * @param string $defaultLength Default length
     */
    public function __construct($defaultLength)
    {
        $this->defaultLength = (int) $defaultLength;
    }

    /**
     * Generate random password.
     *
     * @param int $length Length of password.
     *
     * @return string
     */
    public function generatePassword($length = null)
    {
        if ($length  === null) {
            $length = $this->defaultLength;
        }

        $password = '';
        $charsLength = strlen(self::$chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $position = mt_rand(0, $charsLength);
            $password .= self::$chars{$position};
        }

        return $password;
    }
}
