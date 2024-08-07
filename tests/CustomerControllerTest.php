<?php

namespace App\Tests;
use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class CustomerControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        // Load the database schema
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        if ($metadata) {
            $schemaTool->dropSchema($metadata);
        }

        $schemaTool->createSchema($metadata);
        $this->loadTestData();
    }

    private function loadTestData(): void
    {
        $customer1 = new Customer();
        $customer1->setFullname('John Doe');
        $customer1->setEmail('john.doe@example.com');
        $customer1->setUsername('happycat392');
        $customer1->setGender('female');
        $customer1->setCountry('AU');
        $customer1->setCity('Dubbo');
        $customer1->setPhone('00-8768-3067');
        $customer1->setPassword(md5('password'));
        $this->entityManager->persist($customer1);

        $customer2 = new Customer();
        $customer2->setFullname('Jane Smith');
        $customer2->setEmail('jane.smith@example.com');
        $customer2->setUsername('orangeladybug913');
        $customer2->setGender('female');
        $customer2->setCountry('AU');
        $customer2->setCity('Ballarat');
        $customer2->setPhone('04-7937-5656');
        $customer2->setPassword(md5('password'));
        $this->entityManager->persist($customer2);

        $this->entityManager->flush();
    }

    public function testGetCustomers()
    {
        $this->client->request('GET', '/customers');

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);

        $this->assertIsArray($responseData);
        // Assuming you have 2 customers in test data
        $this->assertCount(2, $responseData); 
        // Check structure of response data
        foreach ($responseData as $customer) {
            $this->assertArrayHasKey('id', $customer);
            $this->assertArrayHasKey('fullname', $customer);
            $this->assertArrayHasKey('email', $customer);
            $this->assertArrayHasKey('country', $customer);
        }
    }

    public function testGetCustomerById()
    {
        $customerId = 1; 
        $this->client->request('GET', "/customers/{$customerId}");

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('fullname', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('username', $data);
        $this->assertArrayHasKey('gender', $data);
        $this->assertArrayHasKey('country', $data);
        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('phone', $data);
    }

    public function testGetCustomerNotFound()
    {
        $invalidCustomerId = 999999; // Have a test with not exsiting id
        $this->client->request('GET', "/customers/{$invalidCustomerId}");

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Customer was not found by id:'.$invalidCustomerId, $data['error']);
    }
}