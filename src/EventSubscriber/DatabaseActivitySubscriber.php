<?php

namespace EventSubscriber;

use App\Entity\Product;
use App\Entity\Category;
use App\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostRemoveEventArgs;
// use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

class DatabaseActivitySubscriber extends AsEntityListener
{

    public function getSubscribedEvents(): array
    {
        return [
            Events::postRemove,
        ];
    }
    public function postRemove(PostRemoveEventArgs $args) : void 
    {
        $this->logActivity('remove', $args->getObject());
    }
    public function logActivity(string $action, mixed $entity) : void 
    {
        if(($entity instanceof Product) && $action === 'remove')
        {
            
        };
        if(($entity instanceof Category) && $action === 'remove')
        {

        };
    }

}
