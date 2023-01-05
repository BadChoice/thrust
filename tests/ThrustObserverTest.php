<?php declare(strict_types=1);

namespace Tests;

use App\Models\Employee;
use App\Models\Invoice;
use BadChoice\Thrust\Facades\ThrustObserver;
use Illuminate\Foundation\Auth\User;

final class ThrustObserverTest extends TestCase
{
    use Concerns\CachesResources;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheResources([
            'employee' => 'App\\Thrust\\Employee',
            'invoice' => 'App\\Thrust\\Invoice',
        ]);
        ThrustObserver::register();
    }

    protected function tearDown(): void
    {
        $this->clearCachedResources();
        parent::tearDown();
    }

    public function testItObservesCreatingEvents(): void
    {
        $invoice = Invoice::create(['total' => 45]);

        $this->assertDatabaseHas('database_actions', [
            'model_type' => $invoice::class,
            'model_id' => $invoice->id,
            'event' => 'created',
            'current' => json_encode(['total' => 45]),
        ]);
    }

    public function testItObservesUpdatingEvents(): void
    {
        $invoice = Invoice::create(['total' => 45]);
        $invoice->update(['total' => 50]);

        $this->assertDatabaseHas('database_actions', [
            'model_type' => $invoice::class,
            'model_id' => $invoice->id,
            'event' => 'updated',
            'original' => json_encode(['total' => 45]),
            'current' => json_encode(['total' => 50]),
        ]);
    }

    public function testItObservesDeletingEvents(): void
    {
        $invoice = Invoice::create(['total' => 45]);
        $invoice->delete();

        $this->assertDatabaseHas('database_actions', [
            'model_type' => $invoice::class,
            'model_id' => $invoice->id,
            'event' => 'deleted',
            'original' => json_encode(['total' => 45]),
        ]);
    }

    public function testItMayBeDisabled(): void
    {
        ThrustObserver::disable();
        $invoice = Invoice::create(['total' => 45]);

        $this->assertDatabaseCount('database_actions', 0);
    }

    public function testModelsMayBeDisabledIndividually(): void
    {
        $this->assertFalse(\App\Thrust\Employee::$observes);
        $employee = Employee::create(['age' => 22]);

        $this->assertDatabaseCount('database_actions', 0);
    }

    public function testItMayHaveAnAuthor(): void
    {
        $this->loadLaravelMigrations();
        $user = new User();
        $user->name = 'Joan';
        $user->email = 'joan@revo.works';
        $user->password = '12345';
        $user->save();

        ThrustObserver::setAuthorNameCallback(fn ($authenticated) => $authenticated->name);

        $this->actingAs($user);
        $invoice = Invoice::create(['total' => 45]);

        $this->assertDatabaseHas('database_actions', [
            'author_name' => $user->name,
            'author_type' => $user::class,
            'author_id' => $user->id,
        ]);
    }

    public function testItDoesNotDoubleEncodeJsonAttributes(): void
    {
        $invoice = Invoice::create([
            'total' => 45,
            'json' => json_encode(['whatever' => 'value']),
        ]);

        $this->assertDatabaseMissing('database_actions', [
            'current' => '{"total":45,"json":"{\"whatever\":\"value\"}"}',
        ]);
        $this->assertDatabaseHas('database_actions', [
            'event' => 'created',
            'current' => '{"total":45,"json":{"whatever":"value"}}',
        ]);

        $invoice->update([
            'json' => json_encode(['whatever' => 'value 2']),
        ]);
        $this->assertDatabaseHas('database_actions', [
            'event' => 'updated',
            'original' => '{"json":{"whatever":"value"}}',
            'current' => '{"json":{"whatever":"value 2"}}',
        ]);

        $invoice->delete();
        $this->assertDatabaseHas('database_actions', [
            'event' => 'deleted',
            'original' => '{"total":45,"json":{"whatever":"value 2"}}',
        ]);
    }
}
