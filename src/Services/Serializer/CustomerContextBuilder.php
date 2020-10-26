<?php
/**
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CustomerContextBuilder implements SerializerContextBuilderInterface
{
    /**
     * @var SerializerContextBuilderInterface
     */
    private $decorated;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @see
     */
    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        //Class being serialized/deserialized
        $resourceClass = $context['resource_class'] ?? null; //Default to null if not set

        if (User::class === $resourceClass && isset($context['groups']) && true === $normalization &&
            $this->authorizationChecker->isGranted(Customer::ROLE_ADMIN)) {
            $context['groups'][] = 'users_read_admin';
        }

        return $context;
    }
}
