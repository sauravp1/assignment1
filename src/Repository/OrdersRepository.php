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
        return $newOrder->getId();
    }
    // /**
    //  * @return Orders[] Returns an array of Orders objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Orders
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function removeOrder(Orders $order)
    {
        $this->manager->remove($order);
        $this->manager->flush();
    }

    public function updateOrder($order, $new_data) 
    {
        // $customer_id = $data["customer_id"];
        // $product_id = $data["product_id"];
        // $price = $data["price"];
        // $customer = $this->customerRepository->findOneBy(['id'=>$customer_id]);
        // $product = $this->productRepository->findOneBy(['id'=>$product_id]);


        empty($new_data['customer_id']) ? true : $order->setCustomerId($new_data['customer_id']);
        empty($new_data['product_id']) ? true : $order->setProductId($new_data['product_id']);
        empty($new_data['price']) ? true : $order->setPrice($new_data['price']);

        $this->manager->flush();
    }
}
