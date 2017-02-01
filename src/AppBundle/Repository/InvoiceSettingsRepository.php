<?php

namespace AppBundle\Repository;

/**
 * InvoiceSettingsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoiceSettingsRepository extends \Doctrine\ORM\EntityRepository
{
    public function findMax(){
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('ins')
            ->from('AppBundle:InvoiceSettings', 'ins')
            ->where('ins.id > 0')
            ->orderBy('ins.updated', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        ;
    }
}