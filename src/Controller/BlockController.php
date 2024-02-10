<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Parts;
use App\Form\CommentType;
use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use App\Repository\PartsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Twig\Environment;


class BlockController extends AbstractController{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,)
         {
        }
    
    #[Route('/', name: 'homepage')]
    public function index(PartsRepository $partsRepository): Response
    {       
            $akskey = $_ENV['AKISMET_KEY'];
            var_dump($akskey);
            return $this->render('parts/index.html.twig', [
                    'parts' => $partsRepository->findAll(),
                ]);
    }

        #[Route('/parts/{slug}', name: 'parts')]
        public function show(
                Request $request,
                Parts $parts,
                CommentRepository $commentRepository,
                #[Autowire('%photo_dir%')] string $photoDir,
            ): Response
        {  

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setParts($parts);

            if ($photo = $form['photo']->getData()) {
                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
                $photo->move($photoDir, $filename);
                $comment->setPhotoFilename($filename);
            }
                

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $context = [
                'user_ip' => $request->getClientIp(),
                'user_agent' => $request->headers->get('user-agent'),
                'referrer' => $request->headers->get('referer'),
                'permalink' => $request->getUri(),
            ];

            $this->bus->dispatch(new CommentMessage($comment->getId(), $context));

            return $this->redirectToRoute('parts', ['slug' => $parts->getSlug()]);
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $commentRepository->getCommentPaginator($parts, $offset);

        return $this->render('parts/show.html.twig', [
            // 'partsall' => $partsRepository->findAll(),
            'parts'    => $parts,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next'     => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form,
        ]);
    }
}