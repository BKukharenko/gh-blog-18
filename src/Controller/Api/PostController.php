<?php

namespace App\Controller\Api;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;

class PostController extends AbstractFOSRestController implements ClassResourceInterface
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Rest\View()
     * @Rest\Get("list")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getPosts()
    {
        $posts = $this->em->getRepository(Post::class)->findAll();

        $formattedPosts = [];
        foreach ($posts as $post) {
            $formattedPosts[] = [
              'id' => $post->getId(),
              'title' => $post->getTitle(),
              'body' => $post->getBody(),
              'created-at' => $post->getCreatedAt(),
              'category' => $post->getCategory()->getName(),
              'author' => $post->getAuthor()->getFullName(),
            ];
        }

        $view = View::create($formattedPosts);
        $view->setFormat('json');

        return $view;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/post/{id}")
     *
     * @param $id
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getPost($id)
    {
        $post = $this->em->getRepository(Post::class)->find($id);

        $formattedPost = [
          'id' => $post->getId(),
          'title' => $post->getTitle(),
          'body' => $post->getBody(),
          'created-at' => $post->getCreatedAt(),
          'category' => $post->getCategory()->getName(),
          'author' => $post->getAuthor()->getFullName(),
        ];

        $view = View::create($formattedPost);
        $view->setFormat('json');

        return $view;
    }
}
