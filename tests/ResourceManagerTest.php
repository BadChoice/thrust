<?php declare(strict_types=1);

namespace Tests;

use BadChoice\Thrust\ResourceManager;

final class ResourceManagerTest extends TestCase
{
    private ResourceManager $resourceManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheResources([
            'invoice' => 'App\\Thrust\\Invoice',
        ]);
        $this->resourceManager = new ResourceManager();
    }

    protected function tearDown(): void
    {
        $this->clearCachedFile();
        parent::tearDown();
    }

    private function cacheResources(array $resources): bool
    {
        return false !== file_put_contents(
            base_path('/bootstrap/cache/thrust.php'),
            '<?php return ' . var_export($resources, true) . ';' . PHP_EOL,
        );
    }

    private function clearCachedFile(): void
    {
        @unlink(base_path('/bootstrap/cache/thrust.php'));
    }

    public function testItProvidesTheCachedResources(): void
    {
        $this->assertEquals(['invoice' => 'App\\Thrust\\Invoice'], $this->resourceManager->resources());
    }

    public function testItProvidesTheModels(): void
    {
        $this->assertEquals(['App\\Models\\Invoice'], $this->resourceManager->models());
    }
}
