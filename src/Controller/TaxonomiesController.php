<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TaxonomiesController extends AbstractController
{
    public function taxonomiesList(): Response
    {
        $tagsRepository = $this->getDoctrine()->getRepository(Tag::class);
        $tagsQuery = $tagsRepository->findAll();

        $categoriesRepository = $this->getDoctrine()->getRepository(Category::class);
        $categoriesQuery = $categoriesRepository->findAll();

        return $this->render('right-sidebar.html.twig', [
            'tags' => $tagsQuery,
            'categories' => $categoriesQuery,
        ]);
    }
}
