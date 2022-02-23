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
       
        $customer_id = $data["customer_id"];
        $product_id = $data["product_id"];
        $price = $data["price"];
        $customer = $this->customerRepository->findOneBy(['id'=>$customer_id]);
        $product = $this->productRepository->findOneBy(['id'=>$product_id]);
       
        
        if (empty($customer_id) || empty($product_id) || empty($price) ){
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
        $customer_name = $order->getCustomerId()->getFirstName();
        $product_name = $order->getProductId()->getName();
        $data = [
            'id' => $order->getId(),
            'customer' => $customer_name,
            'product' => $product_name,
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
