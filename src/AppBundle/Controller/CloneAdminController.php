<?php
/**
 * Created by PhpStorm.
 * User: tigran
 * Date: 8/12/15
 * Time: 3:25 PM
 */
namespace AppBundle\Controller;

/*use Sonata\AdminBundle\Controller\CRUDController as AdmiController;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;*/

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CloneAdminController extends Controller
{

	/**
	 * @return RedirectResponse
	 */
	public function cloneAction($objectId, $count)
	{
	    if((int)$objectId <0 || (int)$count <= 0){

            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $objectId));
        }

        $em = $this->getDoctrine()->getManager();
        $object = $em->getRepository('AppBundle:Booking')->find($objectId);

        do{
            $clonedObject = clone $object;
            $this->admin->create($clonedObject);
            $count --;
        }while($count);


        $this->addFlash('sonata_flash_success', 'Cloned successfully');

        return new RedirectResponse($this->admin->generateObjectUrl('list', $clonedObject));
	}
}