<?php

namespace App\Controller;

use App\Entity\Mission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MissionImageController extends AbstractController
{
    #[Route('/mission/image/{id}', name: 'mission_image')]
    public function show(Mission $mission): Response
    {
        $imageData = $mission->getImageData();
        if (null !== $imageData) {
            if (\is_resource($imageData)) {
                $imageData = stream_get_contents($imageData);
            }

            if (\is_string($imageData) && '' !== $imageData) {
                return new Response($imageData, 200, [
                    'Content-Type' => $mission->getImageMimeType() ?: 'application/octet-stream',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        $legacyImage = $mission->getImage();
        if (null !== $legacyImage && '' !== $legacyImage) {
            $path = ltrim($legacyImage, '/');

            return new RedirectResponse('/images/'.$path);
        }

        throw $this->createNotFoundException('No image found for this mission.');
    }
}
