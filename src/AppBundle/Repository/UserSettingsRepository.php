<?php

namespace AppBundle\Repository;

/**
 * UserSettingsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserSettingsRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByUser()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u')
            ->from('AppBundle:UserSettings', 'u')
            ->innerJoin('u.user', 'cu')
            ->where('cu.roles LIKE :rol')
            ->setParameter('rol', '%ROLE_SUPER_ADMIN%')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }
}
