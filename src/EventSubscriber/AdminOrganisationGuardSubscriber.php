<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdminOrganisationGuardSubscriber implements EventSubscriberInterface
{
    private const ALLOWED_ROUTES_WITHOUT_SELECTION = [
        'admin_organisation_list',
        'admin_organisation_select',
        'admin_userprofil',
        'app_logout',
    ];

    public function __construct(
        private readonly Security $security,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        if (!\is_string($route) || !str_starts_with($route, 'admin_')) {
            return;
        }

        if (\in_array($route, self::ALLOWED_ROUTES_WITHOUT_SELECTION, true)) {
            return;
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return;
        }

        if (null !== $user->getSelectedOrganisation()) {
            return;
        }

        $event->setResponse(new RedirectResponse(
            $this->urlGenerator->generate('admin_organisation_list')
        ));
    }
}
