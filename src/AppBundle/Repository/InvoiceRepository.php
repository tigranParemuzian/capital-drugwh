<?php

namespace AppBundle\Repository;

/**
 * InvoiceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoiceRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByAuthor($userId){

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from('AppBundle:Invoice', 'i')
            ->leftJoin('i.user','u')
            ->leftJoin('i.booking', 'b')
            ->where('u.id =:uid')
            ->setParameter('uid', $userId)
            ->getQuery()->getResult();
    }

    public function findUniqByAuthorAndId($userId, $id){

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from('AppBundle:Invoice', 'i')
            ->leftJoin('i.user','u')
            ->leftJoin('i.booking', 'b')
            ->where('i.id =:id')
            ->andWhere('u.id =:uid')
            ->setParameter('id', $id)
            ->setParameter('uid', $userId)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     *
     */
    public function findByInvoiceIdForPdf($invId){
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('i.number, i.created, i.dueDate, i.terms, i.shippingHandling, i.trackNumber, u.id as userId,
            p.name, p.price, pi.nds,
            SUM(b.count) as counts, SUM(b.subTotal) as total')
            ->from('AppBundle:Invoice', 'i')
            ->leftJoin('i.booking', 'b')
            ->leftJoin('i.user', 'u')
            ->leftJoin('b.product', 'p')
            ->leftJoin('p.productItem', 'pi')
            ->where('i.id = :iid')
            ->groupBy('p.name', 'p.price', 'pi.nds')
            ->setParameter('iid', $invId)
//            ->setMaxResults(1)
            ->getQuery()->getResult()
            ;
    }
}
