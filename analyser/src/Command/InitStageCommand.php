<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Stage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'a:init:stages')]
class InitStageCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function execute(
        InputInterface  $input,
        OutputInterface $output
    ): int
    {
        $stages = [
            1 => '20231002',
            2 => '20231101',
            3 => '20231202',
        ];

        foreach ($stages as $title => $date) {
            $date = new \DateTimeImmutable($date);

            $stage = new Stage();
            $stage->setTitle((string)$title);
            $stage->setStageDateAt($date);
            $this->entityManager->persist($stage);
        }

        $this->entityManager->flush();


        return self::SUCCESS;
    }
}
