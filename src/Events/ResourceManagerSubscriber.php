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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationList;

class ResourceManagerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['checkResourceAvailability', EventPriorities::POST_READ],
        ];
    }

    public function checkResourceAvailability(ExceptionEvent $exceptionEvent): void
    {
        $request = $exceptionEvent->getRequest();
        if (!$exceptionEvent->getThrowable() instanceof NotFoundHttpException) {
            return;
        }
        $uri = $request->getRequestUri();
        $array = explode('/', $uri);
        array_shift($array);
        // Remove empty array values & save name&id of resource
        $dividedUri = array_filter($array, 'strlen');

        // Check if uri exist in route
        if (!isset($dividedUri[1])) {
            return;
        }

        // Check if resource not found
        if (in_array($dividedUri[1], ['products', 'users']) && !isset($dividedUri[3])) {
            $exceptionEvent->setThrowable(
                new NotFoundHttpException($this->createMessage($dividedUri), null, 404)
            );
        }
    }

    /**
     * Create new Constraint violation is id not numeric or doesn't exist.
     *
     * @param array $dividedUri
     *
     * @return ConstraintViolationList
     */
    private function createMessage(array $dividedUri): string
    {
        $entity = substr($dividedUri[1], 0, -1);
        $entityId = $dividedUri[2];
        // Check if id is numeric or string
        return (is_numeric($entityId)) ?
            sprintf('The %s with the given id: \'%s\', does not exist.', $entity, $entityId)
            : sprintf('The given id: \'%s\' must be number', $entityId);
    }
}
