<?php

namespace BadChoice\Thrust\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Clear extends Command
{
    protected $signature = 'thrust:clear';

    protected $description = 'Remove the cached bootstrap files';

    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    public function __invoke(): int
    {
        $file = $this->laravel->bootstrapPath('cache/thrust.php');
        if ($this->filesystem->missing($file)) {
            $this->info('Thrust bootstrap files were not cached');
            return static::SUCCESS;
        }

        if ($this->filesystem->delete($file)) {
            $this->info('Cleared the Thrust cached files');
            return static::SUCCESS;
        }

        $this->warn('Could not delete the Thrust cached files');
        return static::FAILURE;
    }
}
