<?php

declare(strict_types=1);

namespace App\Report;

use App\TitleGroups;

readonly class AppendixD
{

    public function __invoke(): array
    {
        return TitleGroups::UPWORK_GROUPS;
    }
}