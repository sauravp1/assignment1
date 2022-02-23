<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\OrdersRepository;
Use App\Repository\ProductRepository;
use App\Repository\CustomerRepository;
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
     * @Route(path="/add", name="addorder", methods={"POST"})
     */
    public function addOrder(Request $request) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);
       
        $customerId = $data["customer_id"];
        $productId = $data["product_id"];
        $price = $data["price"];
        $customer = $this->customerRepository->findOneBy(['id'=>$customerId]);
        $product = $this->productRepository->findOneBy(['id'=>$productId]);
       
        
        if (empty($customerId) || empty($productId) || empty($price) ){
            throw new NotFoundHttpException("Expecting mandatory parameters:");
        }
        
        
        $this->orderRepository->saveOrder($customer, $product);
        return new JsonResponse(['status' => "order created!"], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="get_one_customer", methods={"GET"})
     */
    public function getOneOrder($id): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id]);
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
     * @Route("/delete/{id}", name="delete_order", methods={"DELETE"})
     */
    public function deleteOrder($id) : JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id'=>$id]);
        $this->orderRepository->removeOrder($order);

        return new JsonResponse(["status:"=>"order deleted"]);


    }

    /**
     * @Route("/update/{id}", name="update_order", methods={"PUT"})
     */
    public function updateOrder($id, Request $request) :JsonResponse
    {
        $order = $this->orderRepository->findOneBy(["id"=>$id]);
        $data = json_decode($request->getContent(), true);



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
        $updated = $this->orderRepository->findOneBy(["id"=>$id]);
        return new JsonResponse(["statcd us: "=> "Order updated"," updated data "=> $updated]);
    }
}
