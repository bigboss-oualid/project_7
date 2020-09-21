<?php
/**
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Events;

use App\Entity\Customer;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber
{
    public function updateJwtData(JWTCreatedEvent $event): void
    {
        /** @var Customer $customer */
        $customer = $event->getUser();
        $data = $event->getData();
        $data['firstName'] = $customer->getFirstName();
        $data['lastName'] = $customer->getLastName();
        $data['roles'] = $customer->getRoles();

        $event->setData($data);
    }
}
