<?php
/**
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Events;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class RouteManagerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['checkRouteAvailability', EventPriorities::POST_READ],
        ];
    }

    public function checkRouteAvailability(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();
        //check if route not be founded, get the message and store it in new violation
        if ($throwable->getPrevious() instanceof
            ResourceNotFoundException) {
            $violations = new ConstraintViolationList(
                [
                    new ConstraintViolation(
                        $throwable->getMessage(),
                        null,
                        [],
                        '',
                        '',
                        ''
                    ),
                ]
            );
            $e = new ValidationException($violations);

            $exceptionEvent->setThrowable($e);
        }
    }
}
