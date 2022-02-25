<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\OrdersRepository;
Use App\Repository\ProductRepository;
use App\Repository\CustomerRepository;
use Exception;
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
        try {
            $data = json_decode($request->getContent(), true);
            // $data = json_decode($request->getContent(), true);
    
            // $temp = $request->getContent();
            // $temp = json_decode($temp);
        
            $customer_id = $data["customer_id"];
            $product_id = $data["product_id"];
            $price = $data["price"];
            $customer = $this->customerRepository->findOneBy(['id'=>$customer_id]);
            $product = $this->productRepository->findOneBy(['id'=>$product_id]);
            // $product = $this->productRepository->findOneBy(['id' => $product_id]);
            
            
            // $price = $product->getPrice();
            
            if (empty($customer_id) || empty($product_id) || empty($price) ){
                throw new NotFoundHttpException("Expecting mandatory parameters:");
            }
            
            
            $id = $this->orderRepository->saveOrder($customer, $product);
            return new JsonResponse(['message' => "order created with id number ".$id], Response::HTTP_CREATED);

        } catch(Exception $e){
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }

    /**
     * @Route("/{id}", name="get_one_customer", methods={"GET"})
     */
    public function getOneOrder($id): JsonResponse
    {
        try {

            $order = $this->orderRepository->findOneBy(['id' => $id]);

            if ($order == null){
                return new JsonResponse(["message" => "Order does not exist"]);
            }

            $customer_name = $order->getCustomerId()->getFirstName();
            $product_name = $order->getProductId()->getName();
            $data = [
                'id' => $order->getId(),
                'customer' => $customer_name,
                'product' => $product_name,
                'price' => $order->getProductId()->getPrice(),
                
            ];
    
            return new JsonResponse(['order' => $data], Response::HTTP_OK);
        } catch(Exception $e){
            return new JsonResponse(["message" => $e->getMessage()]);
        }
    }

    /**
     * @Route("/{id}", name="delete_order", methods={"DELETE"})
     */
    public function deleteOrder($id) : JsonResponse
    {
        try {

            $order = $this->orderRepository->findOneBy(['id'=>$id]);
    
            if ($order == null) {
                return new JsonResponse(["message" => "Order does not exist."]);
            }
    
            $this->orderRepository->removeOrder($order);
    
            return new JsonResponse(["message"=>"order deleted with id number ".$id]);

        } catch(Exception $e) {
            return new JsonResponse([json_decode($e->getMessage())]);
        }
    }

    /**
     * @Route("/{id}", name="update_order", methods={"PUT"})
     */
    public function updateOrder($id, Request $request) :JsonResponse
    {
        try {
            $order = $this->orderRepository->findOneBy(["id"=>$id]);
            // $data = json_decode($request->getContent(), true);
    
            if ($order == null) {
                return new JsonResponse(["message" => "Order not found"]);
            }
    
            $data = json_decode($request->getContent(), true);
    
            if (!empty($data['customer_id'])){
                // Response
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

        } catch (Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()]); 
        }
    }
 }
