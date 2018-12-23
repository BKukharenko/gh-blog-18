<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/create-post", name="create-post")
     * @param Request $request
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
     * @Route("/post/{id}", name="show-post", requirements={"page" = "\d+"})
     * @ParamConverter("post", class="App\Entity\Post")
     * @param Post $post
     */
    public function showPost(Post $post)
    {
        return $this->render('post/show.htm.twig', [
          'post' => $post,
          'category' => $post->getCategory(),
        ]);
    }

    /**
     * @Route("/list", name="list-posts")
     * @param Request $request
     * @param PaginatorInterface $paginator
     */
    public function listPosts(Request $request, PaginatorInterface $paginator)
    {
        $postRepositiry = $this->getDoctrine()->getRepository(Post::class);
        $query = $postRepositiry->findPublishedQuery();

        $pagination = $paginator->paginate(
        $query, $request->query->getInt('page', 1), 10
    );

        return $this->render('post/list.html.twig', [
          'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/comment/{id}/new", methods={"POST"}, name="create-comment")
     * @ParamConverter("id", class="App\Entity\Post")
     * @param Request $request
     * @param Post $post
     */
    public function createComment(Request $request, Post $post): Response
    {
        $comment = new Comment();
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('show-post', [
              'id' => $post->getId(), ]);
            }

        return $this->render('comment/create.htm.twig', [
          'comment_form' => $form->createView(),
          'title' => 'Add New Comment',
        ]);
    }

    public function commentForm(Post $post): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('comment/create.htm.twig', [
          'post' => $post,
          'comment_form' => $form->createView(),
        ]);
    }
}
