<?php

declare(strict_types=1);

namespace App\Controller;


use App\Report\Figure58;
use App\Report\Figure59;
use App\Report\Figure60;
use App\Report\Figure61;
use App\Report\Figure62;
use App\Report\Figure63;
use App\Report\Figure64;
use App\Report\Figure65;
use App\Report\Figure66;
use App\Report\Figure67;
use App\Report\Figure68;
use App\Report\Figure69;
use App\Report\Figure70;
use App\Report\Figure71;
use App\Report\Figure72;
use App\Report\Figure73;
use App\Report\Figure74;
use App\Report\Figure75;
use App\Report\Figure76;
use App\Report\Figure77;
use App\Report\Figure78;
use App\Report\Figure79;
use App\Report\Figure80;
use App\Report\Figure81;
use App\Report\Figure82;
use App\Report\Figure83;
use App\Report\Figure84;
use App\Report\Figure85;
use App\Report\Figure86;
use App\Report\Figure87;
use App\Report\Figure88;
use App\Report\Figure89;
use App\Report\Figure90;
use App\Report\Figure91;
use App\Report\Figure92;
use App\Report\Figure93;
use App\Report\Figure94;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/figure/{number}', name: 'figure')]
class FigureController extends AbstractController
{
    public static function relations(): array
    {
        return [
            58 => Figure58::class,
            59 => Figure59::class,
            60 => Figure60::class,
            61 => Figure61::class,
            62 => Figure62::class,
            63 => Figure63::class,
            64 => Figure64::class,
            65 => Figure65::class,
            66 => Figure66::class,
            67 => Figure67::class,
            68 => Figure68::class,
            69 => Figure69::class,
            70 => Figure70::class,
            71 => Figure71::class,
            72 => Figure72::class,
            73 => Figure73::class,
            74 => Figure74::class,
            75 => Figure75::class,
            76 => Figure76::class,
            77 => Figure77::class,
            78 => Figure78::class,
            79 => Figure79::class,
            80 => Figure80::class,
            81 => Figure81::class,
            82 => Figure82::class,
            83 => Figure83::class,
            84 => Figure84::class,
            85 => Figure85::class,
            86 => Figure86::class,
            87 => Figure87::class,
            88 => Figure88::class,
            89 => Figure89::class,
            90 => Figure90::class,
            91 => Figure91::class,
            92 => Figure92::class,
            93 => Figure93::class,
            94 => Figure94::class,
        ];

    }

    protected function getRelation(int $number): null|string
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
            strtr('report/figure{number}.html.twig', [
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