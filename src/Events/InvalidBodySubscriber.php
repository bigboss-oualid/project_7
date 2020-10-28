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
use App\Exception\InvalidBodyException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

final class InvalidBodySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            kernelEvents::REQUEST => ['handleEmptyBody', EventPriorities::POST_DESERIALIZE],
        ];
    }

    /**
     * Throw exception if the json post is not a valid format or empty.
     *
     * @param RequestEvent $event
     *
     * @throws InvalidBodyException
     */
    public function handleEmptyBody(RequestEvent $event): void
    {
        $request = $event->getRequest();
        //check if $request->get('data') are not deserializer
        if ($request->attributes->get('exception') instanceof NotEncodableValueException) {
            //check if request body is not not empty
            if (strlen($request->getContent()) > 0) {
                $errorMessage = 'The Body format is not a valid json format';
            } else {
                $errorMessage = "The body can't be empty";
            }
            throw new InvalidBodyException($errorMessage);
        }
    }
}
