<?php

namespace App\EventSubscriber;

use  Gedmo\Blameable\BlameableListener;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DoctrineExtensionSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var TranslatableListener
     */
    private $translatableListener;
    /**
     * @var LoggableListener
     */
    private $loggableListener;


    public function __construct(
        TranslatableListener $translatableListener,
        LoggableListener $loggableListener
    ) {
    
        $this->translatableListener = $translatableListener;
        $this->loggableListener = $loggableListener;
    }    

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::FINISH_REQUEST => 'onLateKernelRequest'
        ];
    }
  
    
    public function onLateKernelRequest(FinishRequestEvent $event): void
    {
        $this->translatableListener->setTranslatableLocale($event->getRequest()->getLocale());
    }

}