<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Booking;

/**
 * BookingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookingRepository extends \Doctrine\ORM\EntityRepository
{
    public function findUniq($userId, $prodId){

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from('AppBundle:Booking', 'b')
            ->leftJoin('b.product', 'p')
            ->leftJoin('b.client', 'c')
            ->where('b.status =:st')
            ->andWhere('c.id =:uid')
            ->andWhere('p.id =:pid')
            ->setParameter('st', Booking::IS_NEW)
            ->setParameter('uid', $userId)
            ->setParameter('pid', $prodId)
            ->getQuery()->getOneOrNullResult();

            ;

    }

    /**
     * This function use to get all new bookings
     *
     * @param $userId
     * @return array
     */
    public function findAllNewByClient($userId){
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from('AppBundle:Booking', 'b')
            ->leftJoin('b.client','c')
            ->leftJoin('b.product', 'p')
            ->where('b.status =:st')
            ->andWhere('c.id =:cid')
            ->setParameter('st', Booking::IS_NEW)
            ->setParameter('cid', $userId)
            ->getQuery()->getResult();
            ;
    }
}
