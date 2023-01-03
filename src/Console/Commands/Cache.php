<?php

namespace BadChoice\Thrust\Console\Commands;

use BadChoice\Thrust\Facades\Thrust;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Cache extends Command
{
    protected $signature = 'thrust:cache';

    protected $description = 'Create a Thrust cache file for faster resource registration';

    protected Filesystem $filesystem;
    protected array $resources;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->resources = Thrust::resources(fresh: true);
    }

    public function __invoke(): int
    {
        $success = $this->filesystem->put(
            $this->laravel->bootstrapPath('cache/thrust.php'),
            '<?php return ' . var_export($this->resources, true) . ';' . PHP_EOL
        );

        if ($success) {
            $this->info('Thrust cached successfully!');
            return static::SUCCESS;
        }

        $this->warn('Could not cache the Thrust bootstrap files');
        return static::FAILURE;
    }
}
