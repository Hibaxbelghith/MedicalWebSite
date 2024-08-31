<?php

namespace App\EventSubscriber;

use App\Entity\Produit;
use App\Kernel;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DeleteImageSubscriber implements EventSubscriberInterface
{

    public function __construct(private Kernel $kernel)
    {

    }
    public function onDeleteImage(BeforeEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();
        if(! $entity instanceof Produit){
            return;
        }
        $image = $this->kernel->getProjectDir().'/public/BackOffice/img/'.$entity->getImageProduit();
        unlink($image);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityDeletedEvent::class => 'onDeleteImage',
        ];
    }
}
