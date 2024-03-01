<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', name: __CLASS__)]
class Index extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager): Response
    {

        return $this->render('report/index.html.twig', [
            'title'      => 'index',
            'tables'     => TableController::relations(),
            'figures'    => FigureController::relations(),
            'appendices' => AppendixController::relations(),
        ]);
    }
}

