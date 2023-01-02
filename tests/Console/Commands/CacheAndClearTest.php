<?php declare(strict_types=1);

namespace Tests\Console\Commands;

use Tests\TestCase;

final class CacheAndClearTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        parent::setUp();
        $this->file = base_path('/bootstrap/cache/thrust.php');
        @unlink($this->file);
    }

    public function testItCreatesTheCacheFile(): void
    {
        $this->artisan('thrust:cache')->assertSuccessful();

        $this->assertFileExists($this->file);
        unlink($this->file);
    }

    public function testItDeletesTheCacheFile(): void
    {
        touch($this->file);

        $this->artisan('thrust:clear')->assertSuccessful();

        $this->assertFileDoesNotExist($this->file);
    }
}
