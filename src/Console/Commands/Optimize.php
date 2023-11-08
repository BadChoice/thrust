<?php

namespace BadChoice\Thrust\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\OptimizeCommand;

class Optimize extends Command
{
    protected $signature = 'optimize';

    protected $description = 'Cache the framework bootstrap files';

    public function __invoke(): int
    {
        $this->call(OptimizeCommand::class);
        $this->call(Cache::class);

        return static::SUCCESS;
    }
}
