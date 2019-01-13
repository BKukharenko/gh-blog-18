<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $post = new Post();
        $post->setAuthor($this->getUser());

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
        return $this->render('post/show.html.twig', [
          'post' => $post,
          'category' => $post->getCategory(),
          'tags' => $post->getTags(),
        ]);
    }


  /**
   * @Route("post/edit/{id}", name="post-edit", methods={"GET","POST"})
   */
  public function editPost(Request $request, Post $post): Response
  {

    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    if ($post->getAuthor() !== $this->getUser()){
      return $this->redirectToRoute('homepage');
    }

    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();
      return $this->redirectToRoute('show-post', ['id' => $post->getId()]);
    }

    return $this->render('post/edit.html.twig', [
      'post' => $post,
      'form' => $form->createView(),
    ]);
  }

    /**
     * @Route("/list", name="list-posts")
     * @param Request $request
     * @param PaginatorInterface $paginator
     */
    public function listPosts(Request $request, PaginatorInterface $paginator)
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $query = $postRepository->findPublishedQuery();

        $pagination = $paginator->paginate(
        $query, $request->query->getInt('page', 1), 10
    );

        return $this->render('post/list.html.twig', [
          'pagination' => $pagination,
        ]);
    }

  /**
   * @Route("/category/{slug}", name="posts-by-category")
   * @ParamConverter("category", class="App\Entity\Category")
   */
  public function listPostsByCategory(Request $request, PaginatorInterface $paginator, Category $cat)
  {

    $postRepository = $this->getDoctrine()->getRepository(Post::class);
    $query = $postRepository->findByCategoryQuery($cat->getSlug());

    $pagination = $paginator->paginate(
      $query, $request->query->getInt('page', 1), 10
    );

    return $this->render('post/by-category.html.twig', [
      'pagination' => $pagination,
    ]);
  }

  /**
   * @Route("/tag/{slug}", name="posts-by-tag")
   * @ParamConverter("tag", class="App\Entity\Tag")
   */
  public function listPostsByTag(Request $request, PaginatorInterface $paginator, Tag $tag)
  {

    $postRepository = $this->getDoctrine()->getRepository(Post::class);
    $query = $postRepository->findByTagQuery($tag->getSlug());

    $pagination = $paginator->paginate(
      $query, $request->query->getInt('page', 1), 10
    );

    return $this->render('post/by-tag.html.twig', [
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

        $this->denyAccessUnlessGranted('ROLE_USER');

        $comment = new Comment();
        $post->addComment($comment);
        $comment->setAuthor($this->getUser());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('show-post', [
              'id' => $post->getId(), ]);
            }

        return $this->render('comment/create.html.twig', [
          'comment_form' => $form->createView(),
          'title' => 'Add New Comment',
        ]);
    }

    public function commentForm(Post $post): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('comment/create.html.twig', [
          'post' => $post,
          'comment_form' => $form->createView(),
        ]);
    }
}
