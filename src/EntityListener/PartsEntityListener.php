<?php
namespace App\EntityListener;

use App\Entity\Parts;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Parts::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Parts::class)]
class PartsEntityListener
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function prePersist(Parts $parts, LifecycleEventArgs $event)
    {
        $parts->computeSlug($this->slugger);
    }

    public function preUpdate(Parts $parts, LifecycleEventArgs $event)
    {
        $parts->computeSlug($this->slugger);
    }
}