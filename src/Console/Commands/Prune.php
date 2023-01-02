<?php

namespace BadChoice\Thrust\Console\Commands;

use BadChoice\Thrust\Models\DatabaseAction;
use Illuminate\Console\Command;

class Prune extends Command
{
    protected $signature = 'thrust:prune
                {--days= : The days that will be preserved}';

    protected $description = 'Prune all database actions older than X days [15 by default]';

    public function __invoke(): int
    {
        $days = $this->option('days') ?? 15;

        $query = DatabaseAction::where('created_at', '<', now()->subDays($days));

        do {
            $count = $query->limit(1000)->delete();
        } while ($count > 0);

        return static::SUCCESS;
    }
}
