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
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CustomerContextBuilder implements SerializerContextBuilderInterface
{
    const USER_GET_PRODUCTS = 'products_read';
    const CUSTOMER_GET_USERS = 'admin_user_read';
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

    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);

        // Class being serialized/deserialized
        $resourceClass = $context['resource_class'] ?? null; //Default to null if not set

        if (true === $normalization) {
            $context = $this->addContext($resourceClass, $context);
        }

        return $context;
    }

    /**
     * Add normalization context in resource.
     *
     * @param string $resourceClass
     * @param array  $context
     *
     * @return array
     */
    private function addContext(string $resourceClass, array $context): array
    {
        // Add group if logged Customer has ROLE_USER
        if (Product::class === $resourceClass && !isset($context['groups']) && $this->authorizationChecker->isGranted(Customer::ROLE_USER)
        ) {
            $context['groups'][] = 'products_read';
        }

        // Add group if logged Customer has ROLE_SUPERADMIN
        if (User::class === $resourceClass && $this->authorizationChecker->isGranted(Customer::ROLE_SUPERADMIN)) {
            $context['groups'][] = 'admin_user_read';
        }

        return $context;
    }
}
