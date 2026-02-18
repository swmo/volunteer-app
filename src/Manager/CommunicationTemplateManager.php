<?php

namespace App\Manager;

use App\Entity\CommunicationTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

class CommunicationTemplateManager
{
    public const REGISTRATION_TEMPLATE_KEY = 'registration_email';
    private const FALLBACK_SUBJECT = 'Anmeldung | Burgdorfer Stadtlauf';
    private const FALLBACK_TEMPLATE_PATH = '/templates/emails/registration.html.twig';

    public function __construct(
        private EntityManagerInterface $em,
        private Environment $twig,
        private KernelInterface $kernel
    ) {
    }

    public function getOrCreateRegistrationTemplate(): CommunicationTemplate
    {
        /** @var CommunicationTemplate|null $template */
        $template = $this->em->getRepository(CommunicationTemplate::class)->findOneBy([
            'templateKey' => self::REGISTRATION_TEMPLATE_KEY,
        ]);

        if ($template instanceof CommunicationTemplate) {
            return $template;
        }

        $template = new CommunicationTemplate();
        $template
            ->setTemplateKey(self::REGISTRATION_TEMPLATE_KEY)
            ->setSubject(self::FALLBACK_SUBJECT)
            ->setBody($this->readFallbackTemplate())
            ->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($template);
        $this->em->flush();

        return $template;
    }

    public function renderRegistrationTemplate(array $context): string
    {
        $template = $this->getOrCreateRegistrationTemplate();

        return $this->twig
            ->createTemplate($template->getBody() ?? '')
            ->render($context);
    }

    public function getRegistrationSubject(): string
    {
        $template = $this->getOrCreateRegistrationTemplate();

        return $template->getSubject() ?: self::FALLBACK_SUBJECT;
    }

    private function readFallbackTemplate(): string
    {
        $path = $this->kernel->getProjectDir() . self::FALLBACK_TEMPLATE_PATH;
        $content = @file_get_contents($path);

        if (false === $content) {
            return "{% extends 'emails/base.html.twig' %}\n{% block content %}<p>Bitte Template konfigurieren.</p>{% endblock %}\n";
        }

        return $content;
    }
}

