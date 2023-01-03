<?php declare(strict_types=1);

namespace Tests;

use BadChoice\Thrust\ResourceManager;

final class ResourceManagerTest extends TestCase
{
    use Concerns\CachesResources;

    private ResourceManager $resourceManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheResources([
            'employee' => 'App\\Thrust\\Employee',
            'invoice' => 'App\\Thrust\\Invoice',
            'invoiceDuplicate' => 'App\\Thrust\\InvoiceDuplicate',
        ]);
        $this->resourceManager = new ResourceManager();
    }

    protected function tearDown(): void
    {
        $this->clearCachedResources();
        parent::tearDown();
    }

    public function testItProvidesTheCachedResources(): void
    {
        $this->assertEquals([
            'employee' => 'App\\Thrust\\Employee',
            'invoice' => 'App\\Thrust\\Invoice',
            'invoiceDuplicate' => 'App\\Thrust\\InvoiceDuplicate',
        ], $this->resourceManager->resources());
    }

    public function testItProvidesTheModels(): void
    {
        $this->assertEquals([
            'App\\Models\\Employee',
            'App\\Models\\Invoice',
        ], $this->resourceManager->models());
    }

    public function testItProvidesTheObservableModels(): void
    {
        $this->assertEquals([
            'App\\Models\\Invoice',
        ], $this->resourceManager->models(observable: true));
    }
}
