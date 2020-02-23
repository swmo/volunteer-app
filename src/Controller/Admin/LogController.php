<?php

namespace App\Controller\Admin;

use App\Repository\LogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\LogEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    /**
     * @Route("/admin/log/list", name="admin_log_list")
     */
    public function list(EntityManagerInterface $em)
    {

        $logEntryRepository = $em->getRepository(LogEntry::class);

        return $this->render('admin/log/list.html.twig', [
            'logEntries' => $logEntryRepository->findAll(),
        ]);
    }
}
