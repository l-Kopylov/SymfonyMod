<?php

namespace App\EventSubscriber;

use App\Repository\PartsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $partsRepository;

    public function __construct(Environment $twig, PartsRepository $partsRepository)
    {
        $this->twig = $twig;
        $this->partsRepository = $partsRepository;
    }    


    public function onControllerEvent(ControllerEvent $event): void
    {
        $this->twig->addGlobal('partsall', $this->partsRepository->findAll());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
