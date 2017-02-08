<?php
/**
 * Created by PhpStorm.
 * User: tigran
 * Date: 1/26/17
 * Time: 4:55 PM
 */

namespace AppBundle\Controller\Rest;


use AppBundle\Entity\Product;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Proxies\__CG__\AppBundle\Entity\Booking;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Class RestMainController
 * @package AppBundle\Controller\Rest
 *
 * @RouteResource("bag")
 * @Rest\Prefix("/api")
 * @Rest\NamePrefix("rest_")
 */
class RestMainController extends FOSRestController
{

    /**
     * This function return bag info
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Bag",
     *  description="This function is used to get a all Articles.",
     *  statusCodes={
     *         200="Returned when successful",
     *     }
     * )
     * @Rest\View(serializerGroups={"booking_list"})
     */
    public function getMyBagAction()
    {
        $currentUser = $this->getUser();
        // check isset user and user security role
        if(!is_object($currentUser)) {
            $translated = $this->get('translator')->trans('erorrs.user.not_found');
            return new JsonResponse($translated , Response::HTTP_FORBIDDEN);
        }

        $em = $this->getDoctrine()->getManager();

        // get projectChartfield by project id
        $bookings = $em->getRepository('AppBundle:Booking')->findAllNewByClient($currentUser->getId());

        if(!$bookings){
            return new JsonResponse('0 bookings' , Response::HTTP_NOT_FOUND);
        }
        return $bookings;
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Bag",
     *  description="This function is used to create bag .",
     *  statusCodes={
     *         202="Returned when find",
     *         404="Return when user location not found", },
     *     parameters={
     *          {"name"="slug", "dataType"="string", "required"=true, "description"="slug"},
     *          {"name"="count", "dataType"="string", "required"=true, "description"="count"},
     *      }
     * )
     *
     * This function is used to booking
     * @Rest\View()
     *
     */
    public function postDataAction(Request $request)
    {

        // get current user
        $currentUser = $this->getUser();
        // check isset user and user security role
        if(!is_object($currentUser)) {
            $translated = $this->get('translator')->trans('erorrs.user.not_found');
            return new JsonResponse($translated , Response::HTTP_FORBIDDEN);
        }

        // get request data
        $data = $obj = json_decode($request->getContent());
        $count = (int)$data->count;
        $slug = (string)$data->slug;

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')->findOneBySlug($slug);

        $booking = $em->getRepository('AppBundle:Booking')->findUniq($currentUser->getId(), $product->getId());


            if(!$booking){

                $booking = new Booking();
            }
            if($product->getCount() < $count && $count !=0){

                return new JsonResponse("Ops {$product->getName()} is limited. Limit is {$product->getCount()}" , Response::HTTP_BAD_REQUEST);

            }

        $booking->setClient($currentUser);
        $booking->setProduct($product);
        $booking->setCount($count);
        $cst =$count*$product->getPrice();
        $booking->setCost($cst);
        $booking->setSubTotal($cst);
        $booking->setStatus(Booking::IS_NEW);


        $validator = $this->get('validator');

        $errors = $validator->validate($product);

        if(count($errors) > 0 ) {

            // returned value
            $returnResult = array();

            // check count
            if(count($errors) > 0){

                // loop for error
                foreach($errors as $error){
                    $returnResult[$error->getPropertyPath()] = $error->getMessage();
                }
            }

            // return json response
            return new JsonResponse($returnResult, Response::HTTP_BAD_REQUEST);
        }

        if($count == 0){
            $em->remove($booking);
        }else{
            $em->persist($booking);
        }

        $em->flush();

        return true;
        // get entity manager
        // get validator

    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  section="Bag",
     *  description="This function is used to create booking .",
     *  statusCodes={
     *         202="Returned when find",
     *         404="Return when user location not found", },
     *     parameters={
     *          {"name"="id", "dataType"="string", "required"=true, "description"="User`s start address json array | ['address': 'Poxos Poxosyan 45', 'latitude':'43.522222', 'longitude':'40.256566']"},
     *      }
     * )
     *
     * This function is used to booking
     * @Rest\View()
     *
     */
    public function postDeleteAction(Request $request)
    {
        // get current user
        $currentUser = $this->getUser();
        // check isset user and user security role
        if(!is_object($currentUser)) {
            $translated = $this->get('translator')->trans('erorrs.user.not_found');
            return new JsonResponse($translated , Response::HTTP_FORBIDDEN);
        }

        // get request data
        $data = $obj = json_decode($request->getContent());
        $id = (int)$data->id;
        $em = $this->getDoctrine()->getManager();

        $booking = $em->getRepository('AppBundle:Booking')->findOneById($id);
        $em->remove($booking);
        $em->flush();
        return true;

    }

}