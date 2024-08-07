<?php
namespace App\Tests\Service;

use App\Service\CustomerImporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Customer;

class CustomerImporterTest extends KernelTestCase
{
    public function testImportCustomers()
    {
        $mockResponse = new MockResponse(json_encode([
            'results' => [
                [
                    'nat' => 'AU',
                    'name' => ['first' => 'John', 'last' => 'Doe'],
                    'email' => 'john.doe@example.com',
                    'login' => ['username' => 'johndoe', 'password' => 'password'],
                    'gender' => 'male',
                    'location' => ['city' => 'Sydney'],
                    'phone' => '123456789'
                ]
            ]
        ]));

        $httpClient = new MockHttpClient($mockResponse);
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $importer = new CustomerImporter($entityManager);
        $importer->setHttpClient($httpClient);

        $importer->importCustomers();
        
        // Add assertions to verify the database state

        $customerRepository = $entityManager->getRepository(Customer::class);
        $customer = $customerRepository->findOneBy(['email' => 'john.doe@example.com']);

        $this->assertNotNull($customer);
        $this->assertEquals('John Doe', $customer->getFullName());
        $this->assertEquals('john.doe@example.com', $customer->getEmail());
        $this->assertEquals('johndoe', $customer->getUsername());
        $this->assertEquals('male', $customer->getGender());
        $this->assertEquals('AU', $customer->getCountry());
        $this->assertEquals('Sydney', $customer->getCity());
        $this->assertEquals('123456789', $customer->getPhone());
        $this->assertEquals(md5('password'), $customer->getPassword());
    }
}