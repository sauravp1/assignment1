<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Entity\Customer;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Orders::class);
        $this->manager = $manager;
    }

    
    public function saveOrder($customer, $product)
    {
        $newOrder = new Orders();
        

        $newOrder
            ->setCustomerId($customer)
            ->setProductId($product)
            ->setPrice($product);

        $this->manager->persist($newOrder);
        $this->manager->flush();
    }
    

    public function removeOrder(Orders $order)
    {
        $this->manager->remove($order);
        $this->manager->flush();
    }

    public function updateOrder($order, $new_data) 
    {
        

        empty($new_data['customer_id']) ? true : $order->setCustomerId($new_data['customer_id']);
        empty($new_data['product_id']) ? true : $order->setProductId($new_data['product_id']);
        empty($new_data['price']) ? true : $order->setPrice($new_data['price']);

        $this->manager->flush();
    }
}
