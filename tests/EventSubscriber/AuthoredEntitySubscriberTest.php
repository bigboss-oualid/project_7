<?php
/**
 * BileMo Project.
 *
 * (c) 2020.  BigBoss Walid <bigboss@it-bigboss.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Customer;
use App\Entity\User;
use App\Events\AuthoredEntitySubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AuthoredEntitySubscriberTest extends TestCase
{
    /**
     * Test if we return the expected value.
     */
    public function testConfiguration(): void
    {
        $result = AuthoredEntitySubscriber::getSubscribedEvents();

        $this->assertArrayHasKey(KernelEvents::VIEW, $result);
        $this->assertEquals(
            ['getAuthenticatedCustomer', EventPriorities::PRE_WRITE], $result[KernelEvents::VIEW]
        );
    }

    /**
     * Test if the logged customer is linked to new user.
     *
     * @dataProvider providerSetCustomerCall
     *
     * @param string $className
     * @param bool   $callSetCustomer
     * @param string $method
     */
    public function testSetCustomerCall(string $className, bool $callSetCustomer, string $method): void
    {
        $entityMock = $this->getEntityMock($className, $callSetCustomer);
        $securityMock = $this->getSecurityMock();
        $eventMock = $this->getEventMock($method, $entityMock);

        (new AuthoredEntitySubscriber($securityMock))->getAuthenticatedCustomer($eventMock);
    }

    public function providerSetCustomerCall(): array
    {
        return [
            [User::class, true, Request::METHOD_POST],
            ['NonExisting', false, Request::METHOD_POST],
            [User::class, false, Request::METHOD_GET],
        ];
    }

    /**
     * @return MockObject|Security
     */
    private function getSecurityMock(): MockObject
    {
        //New user has the same company name as his author
        $customer = (new Customer())->setCompany('test');

        $securityMock = $this->getMockBuilder(Security::class)
            ->disableOriginalConstructor()
            ->getMock();

        $securityMock->expects($this->once())
            ->method('getUser')
            ->willReturn($customer);

        return $securityMock;
    }

    /**
     * @param mixed $controllerResult
     *
     * @return MockObject|ViewEvent
     */
    private function getEventMock(string $method, $controllerResult): MockObject
    {
        $requestMock = $this->getMockBuilder(Request::class)
            ->getMock();

        $requestMock->expects($this->once())
            ->method('getMethod')
            ->willReturn($method);

        $eventMock = $this->getMockBuilder(ViewEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $eventMock->expects($this->once())
            ->method('getControllerResult')
            ->willReturn($controllerResult);

        $eventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($requestMock);

        return $eventMock;
    }

    /**
     * @return User|MockObject
     */
    private function getEntityMock(string $className, bool $callSetCustomer): MockObject
    {
        $entityMock = $this->getMockBuilder($className)
            ->setMethods(['setCustomer'])
            ->getMock();
        $entityMock->expects($callSetCustomer ? $this->once() : $this->never())
            ->method('setCustomer');

        return $entityMock;
    }
}
