<?php
/**
 * Created by PhpStorm.
 * User: andranik
 * Date: 9/19/14
 * Time: 2:11 PM
 */

namespace AppBundle\DoctrineListeners;

use AppBundle\Entity\ProductStorage;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\DependencyInjection\Container;

class EventListener
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        // get entityManager
		$em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $mainDir = str_replace('/app', '/', $this->container->getParameter('kernel.root_dir'));

        foreach($uow->getScheduledEntityInsertions() as $entity)
        {
            if ($entity instanceof ProductStorage){
                $bookings = $em->getRepository('AppBundle:Booking')->findRealByProduct($entity->getProduct()->getId());

                dump($bookings); exit;
            }

        }

        foreach($uow->getScheduledEntityUpdates() as $entity)
        {
            if ($entity instanceof ProductStorage){

                $changes = $uow->getEntityChangeSet($entity);

                if(!$entity->getBooking()->isEmpty() && !in_array('count',$changes)){

                    $bIds = [];
                    foreach ($entity->getBooking() as $value){
                        $bIds[] = $value;
                    }

                    $em->getRepository('AppBundle:Booking')->updateStore($bIds, $entity->getLot(), $entity->getSupDate()->format('Y-d-m h:i'), $entity->getExpiryDate()->format('Y-d-m h:i'));

                }elseif($entity->getBooking()->isEmpty() && $entity->getCount() > 0) {

                    $bookings = $em->getRepository('AppBundle:Booking')->findRealByProduct($entity->getProduct()->getId());

                    if(count($bookings)>0){
                        foreach ($bookings as $booking){
                            if($booking->getCount() <= $entity->getCount()){
                                $em->getRepository('AppBundle:Booking')->updateStore([$booking->getId()], $entity->getLot(), $entity->getSupDate()->format('Y-d-m h:i'), $entity->getExpiryDate()->format('Y-d-m h:i'));
                                $entity->setCount($entity->getCount() - $booking->getCount());
                            }
                        }
                    }

                    dump($bookings); exit;
                }



                dump($changes); exit;
            }
        }

        // check data before remove Po data and remove data whose created during create it
        foreach($uow->getScheduledEntityDeletions() as $entity)
        {

        }
    }



}