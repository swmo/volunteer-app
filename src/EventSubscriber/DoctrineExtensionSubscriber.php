<?php

namespace App\EventSubscriber;

use  Gedmo\Blameable\BlameableListener;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
    
    private $authorizationChecker;





    public function __construct(
        TranslatableListener $translatableListener,
        LoggableListener $loggableListener,
        AuthorizationCheckerInterface $authorizationChecker = null,
        TokenStorageInterface $tokenStorage
    )
    {
    
        $this->translatableListener = $translatableListener;
        $this->loggableListener = $loggableListener;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }    

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::FINISH_REQUEST => 'onLateKernelRequest',
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
  
    public function onLateKernelRequest(FinishRequestEvent $event): void
    {
        $this->translatableListener->setTranslatableLocale($event->getRequest()->getLocale());
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if (null === $this->tokenStorage || null === $this->authorizationChecker) {
            return;
        }

        $token = $this->tokenStorage->getToken();
    
        if (null !== $token && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
           $this->loggableListener->setUsername($token->getUser()->getUsername());
        }
    }
}