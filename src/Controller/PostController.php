<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(PostRepository $postRepository, Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset'));
        $paginator = $postRepository->getPaginator($offset);

        return $this->render('post/index.html.twig', [
            'posts' => $paginator,
            'previous' => $offset - PostRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + PostRepository::PAGINATOR_PER_PAGE)
        ]);
    }

    #[Route('/{slug}', name: 'app_show_post')]
    public function show(string $slug, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBy(['slug' => $slug]);

        if ($post) {
            return $this->render('post/show.html.twig', [
                'post' => $post
            ]);
        }

        throw new NotFoundHttpException();
    }
}
