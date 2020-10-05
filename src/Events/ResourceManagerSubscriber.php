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
        //Save Resource name and id in index 2 & 3 of $uri[]
        $uri = $request->getRequestUri();
        $dividedUri = explode('/', $uri);

        //check if uri exist in route
        if (!isset($dividedUri[2])) {
            return;
        }
        //check if resource not found
        if (
            ('products' === $dividedUri[2] || 'users' === $dividedUri[2])
            && (4 === count($dividedUri) || '' === $dividedUri[4])
        ) {
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
        $entity = substr($dividedUri[2], 0, -1);
        $id = $dividedUri[3];
        //check if id is numeric or string
        return (is_numeric($id)) ?
            sprintf('The %s with the given id: \'%s\', does not exist.', $entity, $id)
            : sprintf('The given id: \'%s\' must be number', $id);
    }
}
