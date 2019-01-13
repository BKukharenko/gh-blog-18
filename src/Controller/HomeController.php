<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        return $this->render('home/index.html.twig', [
      'page' => 'Homepage',
      'homepage_description' => 'Check latest posts by click on the Blog
            menu item or click on any category or tag in the sidebar to check posts by taxonomies.',
    ]);
    }
}
