<?php
/**
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AuthoredEntitySubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            kernelEvents::VIEW => ['getAuthenticatedCustomer', EventPriorities::PRE_WRITE],
        ];
    }

    public function getAuthenticatedCustomer(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        /** @var Customer $customer */
        $customer = $this->security->getUser();
        //set user to customer only by POST customers Requests
        if (!$entity instanceof  User || Request::METHOD_POST !== $method) {
            return;
        }
        /* @var User $entity*/
        $entity->setCustomer($customer)
            ->setCompany($customer->getCompany());
    }
}
