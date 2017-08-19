<?php

/**
 * Created by PhpStorm.
 * User: aram
 * Date: 11/9/15
 * Time: 3:16 PM
 */

namespace AppBundle\DoctrineListeners;

use AppBundle\Entity\Booking;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class KernelListener
{
    /**
     * @var
     */
    private $container;

    /**
     * @param $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest($event)
    {
        // get user
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        // get url name
        $routeName = $this->container->get('request')->get('_route');

        if ($routeName === 'sonata_admin_set_object_field_value'){

//            $booking = $em->getRepository('AppBundle:Booking')->find(2198);
//
//            $booking->setCount(343434);
//            $em->persist($booking);
//            $em->flush();

            if(!is_null($event->getRequest()->get('code')) && $event->getRequest()->get('code') === 'admin.storage.product') {

                $event->getRequest()->request->get('value') ? $value = $event->getRequest()->request->get('value'): $value = null;

                $event->getRequest()->get('objectId') ? $objectId = $event->getRequest()->get('objectId') : $objectId = null;
                $event->getRequest()->get('field') ? $field = $event->getRequest()->get('field') : $field = null;

                $storage = $em->getRepository('AppBundle:ProductStorage')->find((int)$objectId);

                $oldCount = $storage->getCount();

                switch ($field){
                    case 'lot':
                        $storage->setLot($value);
                        break;
                    case 'count':
                        $storage->setCount((int)$value);
                        break;
                    case 'supDate':
                        $storage->setSupDate(new \DateTime($value));
                        break;
                    case 'expiryDate':
                        $storage->setExpiryDate(new \DateTime($value));
                        break;
                    default:
                        break;
                };


                if(!is_null($storage->getLot()) && !is_null($storage->getCount()) && !is_null($storage->getExpiryDate()) && !is_null($storage->getSupDate()))
                {

                    switch ($field){
                        case 'count':
                            if($oldCount<$storage->getCount()){
                                $bookings = $em->getRepository('AppBundle:Booking')->findRealByProduct($storage->getProduct()->getId());

                                if($bookings){
                                    foreach ($bookings as $key=>$booking){

                                        if($booking instanceof Booking && $storage->getCount() > 0){

                                            if($booking->getCount() <= $storage->getCount()){
                                                $booking->setLot($storage->getLot());
                                                $booking->setExpiryDate($storage->getExpiryDate());
                                                $booking->setShipDate($storage->getSupDate());

                                                $storage->setCount($storage->getCount() - $booking->getCount());
                                                $booking->setStore($storage);

                                            } else {

                                                $newBooking = clone $booking;

                                                $booking->setLot($storage->getLot());
                                                $booking->setExpiryDate($storage->getExpiryDate());
                                                $booking->setShipDate($storage->getSupDate());
                                                $booking->setCount($storage->getCount());

                                                $storage->setCount(0);

                                                $booking->setStore($storage);
                                                $booking->setCost(round($booking->getProduct()->getPrice() * $booking->getCount(), 2));
                                                $booking->setSubTotal(round($booking->getProduct()->getPrice() * $booking->getCount(), 2));

                                                $newBooking->setCount($booking->getCount() - $storage->getCount());
                                                $newBooking->setCost(round($newBooking->getProduct()->getPrice() * $newBooking->getCount(), 2));
                                                $newBooking->setSubTotal(round($newBooking->getProduct()->getPrice() * $newBooking->getCount(), 2));
                                                $em->persist($newBooking);
                                            }

                                            $em->persist($booking);
                                            $em->persist($storage);
                                        }
                                    }
                                }
                            }else
                            {
                                if(!$storage->getBooking()->isEmpty()){

                                    foreach ($storage->getBooking() as $booking){
                                        if($booking instanceof Booking) {

                                            if ($booking->getCount() >= $storage->getCount() && $storage->getCount() > 0) {
                                                $newBooking = clone $booking;
                                                $newBooking->setCount($booking->getCount() - $storage->getCount());
                                                $newBooking->setCost(round($newBooking->getProduct()->getPrice() * $newBooking->getCount(), 2));
                                                $newBooking->setSubTotal(round($newBooking->getProduct()->getPrice() * $newBooking->getCount(), 2));
                                                $em->persist($newBooking);

                                                $booking->setCount($storage->getCount());
                                                $storage->setCount(0);
                                            }
                                        }

                                        $em->persist($booking);
                                        $em->persist($storage);

                                    }
                                }
                            }

                            $event->getRequest()->request->set('value', $storage->getCount());

                            break;
                        default:
                            if(!$storage->getBooking()->isEmpty()){
                                foreach ($storage->getBooking() as $booking){

                                    if($booking instanceof Booking) {
                                        $booking->setShipDate($storage->getSupDate());
                                        $booking->setExpiryDate($storage->getExpiryDate());
                                        $booking->setLot($storage->getLot());
                                    }

                                    $em->persist($booking);
                                }
                            }
                            break;
                    }
                    $em->flush();
                }

            }

        }
    }
}