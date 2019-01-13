<?php

namespace App\Menu;


use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuBuilder implements ContainerAwareInterface {

  use ContainerAwareTrait;

  private $checker;
  private $factory;

  public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker) {
    $this->factory = $factory;
    $this->checker = $authorizationChecker;
  }

  public function mainMenu() {

    $menu = $this->factory->createItem('root', array(
      'childrenAttributes'    => array(
        'class'             => 'd-flex justify-content-between py-5',
      ),
    ));

    $menu->addChild('Home', ['route' => 'homepage']);
    $menu->addChild('Blog', ['route' => 'list-posts']);

    if ($this->checker->isGranted('ROLE_ADMIN')) {
      $menu->addChild('Create Post', ['route' => 'create-post']);
    }
    if (!$this->checker->isGranted('IS_AUTHENTICATED_FULLY')) {
      $menu->addChild('Login', ['route' => 'app_login']);
      $menu->addChild('Register', ['route' => 'app_register']);
    } else {
      $menu->addChild('Logout', ['route' => 'app_logout']);
    }

    return $menu;

  }

}