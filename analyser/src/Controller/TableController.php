<?php

declare(strict_types=1);

namespace App\Controller;


use App\Report\Table1;
use App\Report\Table2;
use App\Report\Table3;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/table/{number}', name: 'table')]
class TableController extends AbstractController
{
    public static function relations(): array
    {
        return [
            1 => Table1::class,
            2 => Table2::class,
            3 => Table3::class,
        ];
    }

    private static function getRelation(int $number): null|string
    {
        return self::relations()[$number] ?? null;

    }

    public function __invoke(
        int                    $number,
        EntityManagerInterface $entityManager,
    ): Response
    {

        $object    = self::getRelation($number);
        $invokable = new $object($entityManager);

        return $this->render(
            strtr('report/table{number}.html.twig', [
                '{number}' => $number
            ]),
            $invokable() + [
                'tables'     => TableController::relations(),
                'figures'    => FigureController::relations(),
                'appendices' => AppendixController::relations(),
            ],
        );
    }

}