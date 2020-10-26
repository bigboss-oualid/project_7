<?php
/**
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ): void {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        string $operationName = null,
        array $context = []
    ): void {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    /**
     * Handle request to take account of not admin logged user, only if Database query request invoice(s)|customer(s).
     *
     * @param QueryBuilder $queryBuilder
     * @param string       $resourceClass
     */
    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        $customer = $this->security->getUser();

        //Customer with ROLE_ADMIN can see all users, but with ROLE_USER can only see his own users
        if ($customer instanceof Customer && (User::class === $resourceClass)) {
            $rootAlias = $queryBuilder->getRootAliases()[0];

            if (in_array(Customer::ROLE_USER, $customer->getRoles())) {
                $queryBuilder->andWhere("$rootAlias.customer = :customer");
                $queryBuilder->setParameter('customer', $customer);
            } elseif (in_array(Customer::ROLE_ADMIN, $customer->getRoles())) {
                $queryBuilder->andWhere("$rootAlias.company = :company");
                $queryBuilder->setParameter('company', $customer->getCompany());
            }
        }
    }
}
