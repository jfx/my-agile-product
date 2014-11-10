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

use DateTime;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Map3\CoreBundle\Extensions\LocaleDateExtension;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Psr\Log\LoggerInterface;

/**
 * Login listener class.
 *
 * @category  MyAgileProduct
 * @package   User
 * @author    Francois-Xavier Soubirou <soubirou@yahoo.fr>
 * @copyright 2014 Francois-Xavier Soubirou
 * @license   http://www.gnu.org/licenses/   GPLv3
 * @link      http://www.myagileproduct.org
 * @since     3
 */
class LoginListener
{
    /**
     * @var EntityManager Entity manager
     */
    protected $entityManager;

    /**
     * @var UserManagerInterface User manager
     */
    protected $userManager;

    /**
     * @var LoggerInterface Logger
     */
    protected $logger;

    /**
     *
     * @var LocaleDateExtension Date extension
     */
    protected $dateExtension;

    /**
     * Constructor
     *
     * @param EntityManager        $entityManager The doctrine entity manager
     * @param UserManagerInterface $userManager   The user manager
     * @param LoggerInterface      $logger        The logger
     * @param LocaleDateExtension  $dateExtension Date extension
     */
    public function __construct(
        EntityManager $entityManager,
        UserManagerInterface $userManager,
        LoggerInterface $logger,
        LocaleDateExtension $dateExtension
    ) {
        $this->entityManager = $entityManager;
        $this->userManager   = $userManager;
        $this->logger        = $logger;
        $this->dateExtension = $dateExtension;
    }

    /**
     * When authentication succeed (login or cookie remember), unset context
     * of user and refresh list of available products (select box).
     *
     * @param InteractiveLoginEvent $event The event object.
     *
     * @return void
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->logger->debug('onSecurityInteractiveLogin');

        $token = $event->getAuthenticationToken();
        $user = $token->getUser();

        // Unset current product release baseline
        $user->unsetCurrentProduct();
        $token->setAuthenticated(false);

        $repository = $this->entityManager->getRepository(
            'Map3BaselineBundle:Baseline'
        );

        $baselinesFromDB = $repository->findAvailableBaselinesByUser($user);

        $baselines = array();

        foreach ($baselinesFromDB as $row) {

            $releaseDate = new DateTime($row['r_date']);
            $formatedReleaseDate = $this->dateExtension->localeDateFilter(
                $releaseDate
            );
            $releaseLabel  = $row['p_name'].' / '.$row['r_name'].' : ';
            $releaseLabel .= $formatedReleaseDate;

            if (!array_key_exists($releaseLabel, $baselines)) {
                $baselines[$releaseLabel] = array();
            }
            $baselineDate = new DateTime($row['b_date']);
            $formatedBaselineDate = $this->dateExtension->localeDateTimeFilter(
                $baselineDate
            );
            $baselineLabel = $row['b_name'].' : '.$formatedBaselineDate;
            $baselines[$releaseLabel][$row['b_id']] = $baselineLabel;
        }

        $user->setAvailableBaselines($baselines);
        $this->userManager->updateUser($user);
    }
}
