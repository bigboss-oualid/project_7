<?php

use App\DataFixtures\AppFixtures;
use Behat\Gherkin\Node\PyStringNode;
use Behatch\Context\RestContext;
use Behatch\HttpCall\Request;
use Coduo\PHPMatcher\Factory\MatcherFactory;
use Coduo\PHPMatcher\Matcher;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext extends RestContext
{
    /*** used Users inside these tests */
    const USERS = [
        'admin' => 'demo',
        'customerX' => 'demo',
    ];
    const AUTH_URL = '/api/login_check';

    /*** Define how JSON should look like */
    const AUTH_JSON = '
        {
            "username": "%s",
            "password": "%s"
        }
    ';

    /**
     * @var AppFixtures
     */
    private $fixtures;

    /**
     * Test response JSON against some general rule.
     *
     * @var Matcher
     */
    private $matcher;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(Request $request, AppFixtures $fixtures, EntityManagerInterface $em)
    {
        parent::__construct($request);
        $this->fixtures = $fixtures;
        $this->matcher =
            (new MatcherFactory())->createMatcher();
        $this->em = $em;
    }

    /**
     * @Given I am authenticated as :username
     */
    public function iAmAuthenticatedAs(string $username): void
    {
        $this->request->setHttpHeader('Content-Type', 'application/ld+json');
        $this->request->send(
            'POST',
            $this->locatePath(self::AUTH_URL),
            [],
            [],
            sprintf(self::AUTH_JSON, $username, self::USERS[$username])
        );

        $json = json_decode($this->request->getContent(), true);
        //Make sure the token was returned
        $this->assertTrue(isset($json['token']));

        $token = $json['token'];

        $this->request->setHttpHeader(
            'Authorization',
            'Bearer '.$token
        );
    }

    /**
     * @Then the JSON matches expected template:
     */
    public function theJsonMatchesExpectedTemplate(PyStringNode $json): void
    {
        $actual = $this->request->getContent();
        $this->assertTrue($this->matcher->match($actual, $json->getRaw()));
    }

    /**
     * @Then the JSON node :type has :numberOfElements elements
     */
    public function theJsonNodeHasElements(string $key, int $numberOfElements)
    {
        $actual = $this->request->getContent();
        //Convert content to array and count number of users
        $users = count(json_decode($actual, true)[$key]);

        // Make sure the the json key exist
        $this->assertContains($key, $actual);
        // Make sure the returned number of resources is equal to excepted number
        $this->assertTrue($this->matcher->match($users, $numberOfElements));
    }

    /**
     * @BeforeScenario @createSchema
     *
     * @throws ToolsException
     */
    public function createSchema(): void
    {
        //Get entity metadata
        $classes = $this->em->getMetadataFactory()->getAllMetadata();

        //Drop & create schema
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);

        //load fixtures... & execute
        $purger = new ORMPurger($this->em);
        $fixturesExecutor =
            new ORMExecutor(
                $this->em,
                $purger
            );
        $fixturesExecutor->execute([
            $this->fixtures,
        ]);
    }
}
