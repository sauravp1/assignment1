<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use ErrorException;
use JsonException;
use PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CustomerSiteController
 * @package App\Controller
 *
 * @Route(path="/customer")
 */

class CustomerController extends AbstractController
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route(path="", name="add_customer", methods={"POST"})
     */

     public function addCustomer(Request $request): JsonResponse
     {
         $data = json_decode($request->getContent(), true);

         try {

             $firstName = $data["firstName"];
             $lastName = $data["lastName"];
             $email = $data["email"];
             $phoneNumber = $data["phoneNumber"];

             $id = $this->customerRepository->saveCustomer($firstName, $lastName, $email, $phoneNumber);

             return new JsonResponse(['message' => "Customer created with id number {$id}"], Response::HTTP_CREATED);

         } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {

             return new JsonResponse(["Error message" => "Enter valid arguments."]);

         } catch (ErrorException $e) {

            return new JsonResponse(["Error message" => "Enter valid arguments"]);
 
        }
     }



    /**
     * @Route("/{id}", name="get_one_customer", methods={"GET"})
     */
    public function getOneCustomer($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        if (is_null($customer)) {

            return new JsonResponse(["Error message" => "Customer Not found"]);
        }

        $data = [
            'id' => $customer->getId(),
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'email' => $customer->getEmail(),
            'phoneNumber' => $customer->getPhoneNumber(),
        ];

        return new JsonResponse(['customer' => $data], Response::HTTP_OK);
    }

       /**
     * @Route("", name="get_all_customers", methods={"GET"})
     */
    public function getAllCustomers(): JsonResponse
    {
        $customers = $this->customerRepository->findAll();
        $data = [];

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phoneNumber' => $customer->getPhoneNumber(),
            ];
        }

        return new JsonResponse(['customers' => $data], Response::HTTP_OK);
    }


    /**
     * @Route("/{id}", name="update_customer", methods={"PUT"})
     */
    public function updateCustomer($id, Request $request): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        if (is_null($customer)){

            return new JsonResponse(["Error message" => "Customer not found"]);
        }

        $data = json_decode($request->getContent(), true);

        $this->customerRepository->updateCustomer($customer, $data);

        return new JsonResponse(['message' => 'customer updated with id number '.$id, "data"=>$data]);
    }


    /**
     * @Route("/{id}", name="delete_customer", methods={"DELETE"})
     */
    public function deleteCustomer($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        if (is_null($customer)) {

            return new JsonResponse(["Error message" => "Customer Not Found"]);
        }

        $this->customerRepository->removeCustomer($customer);

        return new JsonResponse(['message' => 'customer deleted with id number '.$id]);
    }

}

?>