<?php

namespace BadChoice\Thrust\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\OptimizeClearCommand;

class OptimizeClear extends Command
{
    protected $signature = 'optimize:clear';

    protected $description = 'Remove the cached bootstrap files';

    public function __invoke(): int
    {
        $this->call(OptimizeClearCommand::class);
        $this->call(Clear::class);

        return static::SUCCESS;
    }
}
