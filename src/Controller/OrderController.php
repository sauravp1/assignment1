<?php

namespace App\Controller;

use App\Repository\OrdersRepository;
Use App\Repository\ProductRepository;
use App\Repository\CustomerRepository;
use ErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
* @Route("/order", name="order")
*@package App\Controller
*/
class OrderController extends AbstractController
{
    private $orderRepository;
    private $customerRepository;
    private $productRepository;

    public function __construct(OrdersRepository $orderRepository, ProductRepository $productRepository, CustomerRepository $customerRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
        
    }
    
    /**
     * @Route(path="", name="addorder", methods={"POST"})
     */
    public function addOrder(Request $request) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);
       
        try {

            $customerId = $data["customer_id"];
            $productId = $data["product_id"];
            $price = $data["price"];
            $customer = $this->customerRepository->findOneBy(['id'=>$customerId]);
            $product = $this->productRepository->findOneBy(['id'=>$productId]);

            $id = $this->orderRepository->saveOrder($customer, $product);

            return new JsonResponse(['message' => "order created with id number ".$id], Response::HTTP_CREATED);

        } catch(ErrorException $e) {
            
            return new JsonResponse(["Error message" => "Enter valid arguments"]);

        } catch(\Doctrine\DBAL\Exception\NotNullConstraintViolationException $e) {

            return new JsonResponse(["Error message" => "Enter valid arguments"]);
        }      
    }

    /**
     * @Route("/{id}", name="get_one_customer", methods={"GET"})
     */
    public function getOneOrder($id): JsonResponse
    {   
        $order = $this->orderRepository->findOneBy(['id' => $id]);

        if (is_null($order)) {

            return new JsonResponse(["Error message" => "Order not found"]);
        }

        $customerName = $order->getCustomerId()->getFirstName();
        $productName = $order->getProductId()->getName();
        $data = [
            'id' => $order->getId(),
            'customer' => $customerName,
            'product' => $productName,
            'price' => $order->getProductId()->getPrice(),
        ];

        return new JsonResponse(['order' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="delete_order", methods={"DELETE"})
     */
    public function deleteOrder($id) : JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id'=>$id]);

        if (is_null($order)) {

            return new JsonResponse(["Error message" => "Order not found"]);

        }

        $this->orderRepository->removeOrder($order);

        return new JsonResponse(["message"=>"order deleted with id number ".$id]);
    }

    /**
     * @Route("/{id}", name="update_order", methods={"PUT"})
     */
    public function updateOrder($id, Request $request) :JsonResponse
    {
        $order = $this->orderRepository->findOneBy(["id"=>$id]);

        if (is_null($order)) {

            return new JsonResponse(["Error message" => "Order not found"]);

        }

        $data = json_decode($request->getContent(), true);

        if (is_null($data)){
            return new JsonResponse(["Error message" => "Enter data to update"]);
        }

        if (!empty($data['customer_id'])){
            $data["customer_id"]=$this->customerRepository->findOneBy(["id"=>$data["customer_id"]]);
        }
        if (!empty($data['product_id']))
        {
            $data["product_id"] = $this->productRepository->findOneBy(["id"=>$data["product_id"]]);
        }

        if (!empty($data['price']) )
        {
            $data["price"] = $this->productRepository->findOneBy(["id"=>$data["product_id"]]);
        }
 
        $this->orderRepository->updateOrder($order, $data);
        return new JsonResponse(["message"=> "Order updated with id number ".$id]);
    }
}
