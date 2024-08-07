<?php

namespace App\Service;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CustomerImporter
{
    private $em;
    private $httpClient;
    private $thirdPartyApiUrl;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->httpClient = HttpClient::create();
        $this->thirdPartyApiUrl = sprintf('%s?results=%s&nat=%s', $_ENV['THIRD_PARTY_API_URL'], $_ENV['THIRD_PARTY_FETCH_COUNT'], $_ENV['THIRD_PARTY_NATIONALITY']);
 
    }

    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }
    public function importCustomers()
    {
        $response = $this->httpClient->request('GET', $this->thirdPartyApiUrl);

        $data = $response->toArray();

        foreach ($data['results'] as $userData) {

            $customer = $this->em->getRepository(Customer::class)->findOneBy(['email' => $userData['email']]);

            if (!$customer) {
                $customer = new Customer();
            }

            $customer->setFullName($userData['name']['first']. " ".$userData['name']['last']);
            $customer->setEmail($userData['email']);
            $customer->setUsername($userData['login']['username']);
            $customer->setGender($userData['gender']);
            $customer->setCountry($userData['nat']);
            $customer->setCity($userData['location']['city']);
            $customer->setPhone($userData['phone']);
            $customer->setPassword(md5($userData['login']['password']));
            $this->em->persist($customer);
        }

        $this->em->flush();
    }
}