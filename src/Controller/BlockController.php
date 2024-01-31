<?php

namespace App\Controller;

use App\Entity\Parts;
use App\Repository\CommentRepository;
use App\Repository\PartsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class BlockController extends AbstractController{
    #[Route('/', name: 'homepage')]
    public function index(Environment $twig, PartsRepository $partsRepository): Response
    {
            return new Response($twig->render('parts/index.html.twig', [
                    'parts' => $partsRepository->findAll(),
                ]));
    }

        #[Route('/parts/{id}', name: 'parts')]
    public function show(Environment $twig, Parts $parts, CommentRepository $commentRepository): Response
    {
        return new Response($twig->render('parts/show.html.twig', [
            'parts' => $parts,
            'comments' => $commentRepository->findBy(['parts' => $parts], ['createdAt' => 'DESC']),
        ]));
    }
}