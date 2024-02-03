<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Parts;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PartsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class BlockController extends AbstractController{
    #[Route('/', name: 'homepage')]
    public function index(PartsRepository $partsRepository): Response
    {
            return $this->render('parts/index.html.twig', [
                    'parts' => $partsRepository->findAll(),
                ]);
    }

        #[Route('/parts/{slug}', name: 'parts')]
    public function show(Request $request, Parts $parts, CommentRepository $commentRepository, PartsRepository $partsRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($parts, $offset);

        return $this->render('parts/show.html.twig', [
            'partsall' => $partsRepository->findAll(),
            'parts'    => $parts,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next'     => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form,
        ]);
    }
}