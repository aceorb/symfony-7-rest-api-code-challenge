<?php
namespace App\Controller;

use App\Entity\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use App\Exception\CustomerNotFoundException;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: "/customers", name: "customers_")]
class CustomerController extends AbstractController
{
    /**
     * @param CustomerRepository $customers
     */
    public function __construct(private readonly CustomerRepository $customers)
    {
    }

    #[Route(path: "test", name: "test", methods: ["GET"])]
    public function test()
    {
        return new JsonResponse(['data' =>'test']);
    }

    #[Route(path: "", name: "all", methods: ["GET"])]
    public function getCustomers()
    {
        $data = $this->customers->findAll();
        $returnData = [];
        foreach ($data as $value) {
            $returnData[] = [
                'id' => $value->getId(),
                'fullname' => $value->getFullname(),
                'email' => $value->getEmail(),
                'country' => $value->getCountry(),
            ];
        }
        return new JsonResponse($returnData);
    }

    #[Route(path: "/{id}", name: "byId", methods: ["GET"])]
    public function getCustomer(int $id)
    {
        $data = $this->customers->findOneBy(["id" => $id]);
        if ($data) {
            $returnData = [
                'fullname' => $data->getFullname(),
                'email' => $data->getEmail(),
                'username' => $data->getUsername(),
                'gender' => $data->getGender(),
                'country' => $data->getCountry(),
                'city' => $data->getCity(),
                'phone' => $data->getPhone(),
            ];
            return new JsonResponse($returnData);
        } else {
            return new JsonResponse(["error" => "Customer was not found by id:" . $id], Response::HTTP_NOT_FOUND);
        }
    }
}