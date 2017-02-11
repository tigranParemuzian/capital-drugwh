<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Invoice;

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
            ->orderBy('i.created','DESC')
            ->setParameter('uid', $userId)
            ->getQuery()->getResult();
    }

    public function findUniqByAuthorAndId($userId, $id, $state = null){

        $q = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from('AppBundle:Invoice', 'i')
            ->leftJoin('i.user','u')
            ->leftJoin('i.booking', 'b')
            ->where('i.number =:id')
            ->andWhere('u.id =:uid');
        $state === Invoice::IS_NEW ? $q->andWhere('i.status =:st') :'';
        $q    ->setParameter('id', $id)
            ->setParameter('uid', $userId)
            ;
        $state === Invoice::IS_NEW ? $q->setParameter('st', $state) :'';

        return $q->getQuery()->getOneOrNullResult();
    }

    /**
     *
     */
    public function findByInvoiceIdForPdf($invId){
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('i.number, i.created, i.dueDate, i.terms, i.shippingHandling, i.trackNumber,
            u.id as userId, u.customerId as customerId,
            p.name, p.price, pi.nds, pi.strength,
            SUM(b.count) as counts, SUM(b.subTotal) as total')
            ->from('AppBundle:Invoice', 'i')
            ->leftJoin('i.booking', 'b')
            ->leftJoin('i.user', 'u')
            ->leftJoin('b.product', 'p')
            ->leftJoin('p.productItem', 'pi')
            ->where('i.number = :iid')
            ->groupBy('p.name', 'p.price', 'pi.nds')
            ->setParameter('iid', $invId)
//            ->setMaxResults(1)
            ->getQuery()->getResult()
            ;
    }
}
