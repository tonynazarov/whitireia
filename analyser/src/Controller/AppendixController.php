<?php

declare(strict_types=1);

namespace App\Controller;


use App\Report\AppendixB;
use App\Report\AppendixC;
use App\Report\AppendixD;
use App\Report\AppendixE;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/appendix/{letter}', name: 'appendix')]
class AppendixController extends AbstractController
{
    public static function relations(): array
    {
        return [
            'B' => AppendixB::class,
            'C' => AppendixC::class,
            'D' => AppendixD::class,
            'E' => AppendixE::class,
        ];

    }

    protected function getRelation(string $letter): null|string
    {
        return self::relations()[$letter] ?? null;
    }


    public function __invoke(
        string                 $letter,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $letter    = strtoupper($letter);
        $invokable = $this->getRelation($letter);

        return $this->render(
            strtr('report/Appendix{letter}.html.twig', [
                '{letter}' => $letter,
            ]),
            [
                'title' => 'Appendix ' . $letter,
                'data'  => (new $invokable)(),
                'tables'     => TableController::relations(),
                'figures'    => FigureController::relations(),
                'appendices' => AppendixController::relations(),

            ]
        );
    }

}