<?php

declare(strict_types=1);

namespace App\Report;

use App\TitleGroups;

readonly class AppendixE
{

    public function __invoke(): array
    {
        return TitleGroups::JOB_POSTING_SERVICE_GROUPS;
    }
}