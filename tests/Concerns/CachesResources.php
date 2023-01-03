<?php declare(strict_types=1);

namespace Tests\Concerns;

trait CachesResources
{
    private function cacheResources(array $resources): bool
    {
        return false !== file_put_contents(
            base_path('/bootstrap/cache/thrust.php'),
            '<?php return ' . var_export($resources, true) . ';' . PHP_EOL,
        );
    }

    private function clearCachedResources(): void
    {
        @unlink(base_path('/bootstrap/cache/thrust.php'));
    }
}
