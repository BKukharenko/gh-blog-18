<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

      $form = $this->createForm(PostType::class, $post);
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

  /**
   * @Route("/post/{id}", name="show-post", requirements={"page"="\d+"})
   * @ParamConverter("post", class="App\Entity\Post")
   */
  public function showPost(Post $post) {

    return $this->render('post/show.htm.twig', [
      'post' => $post,
      'category' => $post->getCategory(),
    ]);
    }
}
