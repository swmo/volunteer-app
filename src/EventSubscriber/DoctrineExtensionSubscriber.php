<?php

namespace App\EventSubscriber;

use Gedmo\Loggable\LoggableListener;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
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
        TokenStorageInterface $tokenStorage,
        ?AuthorizationCheckerInterface $authorizationChecker = null
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

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (null === $this->tokenStorage || null === $this->authorizationChecker) {
            return;
        }

        $token = $this->tokenStorage->getToken();
    
        if (null !== $token && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $token->getUser();
            if (\is_object($user) && method_exists($user, 'getUserIdentifier')) {
                $this->loggableListener->setUsername($user->getUserIdentifier());
            }
        }
    }
}
