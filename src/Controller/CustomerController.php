<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Doctrine\DBAL\Exception;
use Error;
use ErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

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
            } catch (ErrorException $e) {
                return new JsonResponse(["message" => "Enter value for all field"], Response::HTTP_BAD_REQUEST);
            }

            if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber)){
                throw new NotFoundHttpException("Expecting mandatory parameters:");
            }

            $id = $this->customerRepository->saveCustomer($firstName, $lastName, $email, $phoneNumber);

            return new JsonResponse(['message' => "Customer created with id number {$id}"], Response::HTTP_CREATED);
    
     

    }
        



    /**
     * @Route("/{id}", name="get_one_customer", methods={"GET"})
     */
    public function getOneCustomer($id): JsonResponse
    {

        try {

            $customer = $this->customerRepository->findOneBy(['id' => $id]);

            if ($customer == null) {
                return new JsonResponse(["message" => "Customer does not exist"]);
            }
    
            $data = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phoneNumber' => $customer->getPhoneNumber(),
            ];
    
            return new JsonResponse(['customer' => $data], Response::HTTP_OK);
        } catch(Exception $e){
            return new JsonResponse([json_decode($e->getMessage())]);
        }
    }

       /**
     * @Route("", name="get_all_customers", methods={"GET"})
     */
    public function getAllCustomers(): JsonResponse
    {
        try {
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
        } catch (Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }


    /**
     * @Route("/{id}", name="update_customer", methods={"PUT"})
     */
    public function updateCustomer($id, Request $request): JsonResponse
    {
        try {

            $customer = $this->customerRepository->findOneBy(['id' => $id]);
            $data = json_decode($request->getContent(), true);

            if ($customer == null){
                return new JsonResponse(["message" => "Customer does not exist."]);
            }
    
            $this->customerRepository->updateCustomer($customer, $data);
    
            return new JsonResponse(['message' => 'customer updated with id number '.$id, "data"=>$data]);

        } catch (Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }


    /**
     * @Route("/{id}", name="delete_customer", methods={"DELETE"})
     */
    public function deleteCustomer($id): JsonResponse
    {
        try {

            $customer = $this->customerRepository->findOneBy(['id' => $id]);

            if ($customer == null){
                return new JsonResponse((["message" => "Customer does not exist."]));
            }
    
            $this->customerRepository->removeCustomer($customer);
    
            return new JsonResponse(['message' => 'customer deleted with id number '.$id]);
        
        } catch (Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }

}

?>