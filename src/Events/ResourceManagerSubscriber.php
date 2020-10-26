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
use App\Exception\ResourceNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ResourceManagerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['checkResourceAvailability', EventPriorities::POST_READ],
        ];
    }

    /**
     * @param RequestEvent $event
     *
     * @throws ResourceNotFoundException
     */
    public function checkResourceAvailability(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->attributes->get('exception') instanceof NotFoundHttpException) {
            return;
        }

        //Save Resource name and id in index 2 & 3 of $uri[]
        $uri = $request->getRequestUri();
        $dividedUri = explode('/', $uri);
        //check if resource not found
        if (
            ('products' === $dividedUri[2] || 'users' === $dividedUri[2])
            && (4 === count($dividedUri) || '' === $dividedUri[4])
        ) {
            $entity = substr($dividedUri[2], 0, -1);
            $id = $dividedUri[3];
            //check if id is numeric or string
            $message = (is_numeric($id)) ?
                sprintf('The %s with the given id: \'%s\', does not exist.', $entity, $id)
                : sprintf('The given id: \'%s\' must be number', $id);

            throw new ResourceNotFoundException($message);
        }
    }
}
