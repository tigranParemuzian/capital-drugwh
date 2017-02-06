<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{

	use ContainerAwareTrait;

    public function topMenu(FactoryInterface $factory, array $options)
    {
		$em = $this->container->get('doctrine')->getManager();
		$menues = $em->getRepository('AppBundle:Menu')->findMenus();

        $menu = $factory->createItem('root');
        $menu->addChild('Homepage', array('route' => 'homepage'));


$user = $this->container->get('security.token_storage')->getToken()->getUser();
		foreach ($menues as $singleMenu)
		{

			$menu->addChild($singleMenu['name'])
				->setUri($singleMenu['slug']);
		}

		$t = $this->container->get('request')->getPathInfo();
//		dump($t);
		if (is_object($user) && true ==$this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
			$menu->addChild($user->getUsername())
			->setUri('#')
			->setAttribute('class','dropdown text-capitalize')
			->setLinkAttributes(array('class'=>"dropdown-toggle", 'data-toggle'=>"dropdown",
				'role'=>"button", 'aria-haspopup'=>"true", 'aria-expanded'=>"false"));
			$menu["{$user->getUsername()}"]->addChild('Dashboard', array('route'=>'sonata_admin_dashboard'));
			$menu["{$user->getUsername()}"]->addChild('Orders', array('route'=>'submit_order'));
			$menu["{$user->getUsername()}"]->addChild('Profile edit', array('route'=>'fos_user_profile_edit'));
			$menu["{$user->getUsername()}"]->addChild('Profile Show', array('route'=>'fos_user_profile_show'));
			$menu["{$user->getUsername()}"]->addChild('Logout', array('route'=>'fos_user_security_logout'));
			// The current (may be switched) username.

		}elseif(is_object($user) && true ==$this->container->get('security.authorization_checker')->isGranted('ROLE_USER')){
			$menu->addChild($user->getUsername())
				->setUri('#')
				->setAttribute('class','dropdown text-capitalize')
				->setLinkAttributes(array('class'=>"dropdown-toggle", 'data-toggle'=>"dropdown",
					'role'=>"button", 'aria-haspopup'=>"true", 'aria-expanded'=>"false"));
			$menu["{$user->getUsername()}"]->addChild('Orders', array('route'=>'submit_order'));
			$menu["{$user->getUsername()}"]->addChild('Profile edit', array('route'=>'fos_user_profile_edit'));
			$menu["{$user->getUsername()}"]->addChild('Profile Show', array('route'=>'fos_user_profile_show'));
			$menu["{$user->getUsername()}"]->addChild('Logout', array('route'=>'fos_user_security_logout'));
		}else{
			$menu->addChild('Login', array('route' => 'fos_user_security_login'));
		}



        return $menu;
    }
}