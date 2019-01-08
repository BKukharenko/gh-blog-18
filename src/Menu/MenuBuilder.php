<?php

namespace App\Menu;


use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface {

  use ContainerAwareTrait;

  public function mainMenu(FactoryInterface $factory) {

    $menu = $factory->createItem('root');

    $menu->addChild('Home', ['route' => 'homepage']);
    $menu->addChild('Blog', ['route' => 'list-posts']);
    $menu->addChild('Create Post', ['route' => 'create-post']);

    return $menu;

  }

}