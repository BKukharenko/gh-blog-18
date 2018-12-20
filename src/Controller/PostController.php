<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/create-post", name="create-post")
     */
    public function createPost(Request $request)
    {
      $post = new Post();

      $form = $this->createForm(PostType::class);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('show-post', [
          'id' => $post->getId(),
        ]);
      }

        return $this->render('post/create.html.twig', [
          'form' => $form->createView(),
        ]);
    }
}
