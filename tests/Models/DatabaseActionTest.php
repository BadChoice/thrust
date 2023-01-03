<?php declare(strict_types=1);

namespace Tests\Models;

use App\Models\Employee;
use App\Models\Invoice;
use BadChoice\Thrust\Models\Enums\DatabaseActionEvent;
use BadChoice\Thrust\Models\DatabaseAction;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use LogicException;
use Tests\TestCase;

final class DatabaseActionTest extends TestCase
{
    public function testItIsPersistedWithCastedAttributes(): void
    {
        DatabaseAction::create([
            'author_name' => 'Joan',
            'author_type' => 'App\\Models\\Employee',
            'author_id' => 32,
            'model_type' => 'App\\Models\\Invoice',
            'model_id' => 25,
            'event' => DatabaseActionEvent::UPDATED,
            'original' => ['total' => 45],
            'current' => ['total' => 60],
            'ip' => '215.6.18.147',
        ]);

        $this->assertDatabaseCount('database_actions', 1);

        $action = DatabaseAction::first();

        $this->assertMatchesRegularExpression('/^[0-9A-Za-z]{26}$/', $action->id, 'The ID is not a valid ULID');
        $this->assertEquals('Joan', $action->author_name);
        $this->assertEquals('App\\Models\\Employee', $action->author_type);
        $this->assertEquals(32, $action->author_id);
        $this->assertEquals('App\\Models\\Invoice', $action->model_type);
        $this->assertEquals(25, $action->model_id);
        $this->assertInstanceOf(DatabaseActionEvent::class, $action->event, 'The EVENT attribute is not an Enum');
        $this->assertEquals(DatabaseActionEvent::UPDATED, $action->event);
        $this->assertInstanceOf(Collection::class, $action->original, 'The ORIGINAL attribute is not a Collection');
        $this->assertEquals(45, $action->original->get('total'));
        $this->assertInstanceOf(Collection::class, $action->current, 'The CURRENT attribute is not a Collection');
        $this->assertEquals(60, $action->current->get('total'));
        $this->assertEquals('215.6.18.147', $action->ip);
        $this->assertInstanceOf(CarbonImmutable::class, $action->created_at, 'The CREATED_AT attribute is not an Immutable Datetime');
    }

    public function testItIsImmutable(): void
    {
        $action = DatabaseAction::create([
            'author_name' => 'Joan',
            'model_type' => 'App\\Models\\Invoice',
            'model_id' => 25,
            'event' => DatabaseActionEvent::UPDATED,
        ]);

        try {
            $action->update(['author_name' => 'Josep']);
        } catch(LogicException $e) {
            $this->assertStringContainsString('cannot be updated', $e->getMessage());
        }

        $this->assertDatabaseMissing('database_actions', ['author_name' => 'Josep']);
    }

    public function testItMayHaveARelatedAuthor(): void
    {
        $actionWithoutRelatedAuthor = DatabaseAction::create([
            'author_name' => 'Joan',
            'model_type' => 'App\\Models\\Invoice',
            'model_id' => 25,
            'event' => DatabaseActionEvent::UPDATED,
        ]);

        $this->assertNull($actionWithoutRelatedAuthor->author);

        $author = Employee::create([
            'age' => 22,
        ]);

        $actionWithRelatedAuthor = new DatabaseAction();
        $actionWithRelatedAuthor->author_name = 'Maria';
        $actionWithRelatedAuthor->author()->associate($author);
        $actionWithRelatedAuthor->model_type = 'App\\Models\\Invoice';
        $actionWithRelatedAuthor->model_id = 25;
        $actionWithRelatedAuthor->event = DatabaseActionEvent::CREATED;
        $actionWithRelatedAuthor->save();

        $this->assertDatabaseHas('database_actions', [
            'author_name' => 'Maria',
            'author_type' => $author::class,
            'author_id' => $author->id,
            'event' => 'created',
        ]);

        $this->assertInstanceOf(Employee::class, $actionWithRelatedAuthor->author);
        $this->assertEquals(22, $actionWithRelatedAuthor->author->age);
    }

    public function testItHasARelatedModelThatMayExists(): void
    {
        $actionWithoutRelatedModel = DatabaseAction::create([
            'author_name' => 'Joan',
            'model_type' => 'App\\Models\\Post',
            'model_id' => 25,
            'event' => DatabaseActionEvent::UPDATED,
        ]);

        $this->assertNull($actionWithoutRelatedModel->model);

        $invoice = Invoice::create([
            'total' => 45,
        ]);

        $actionWithRelatedModel = new DatabaseAction();
        $actionWithRelatedModel->author_name = 'Maria';
        $actionWithRelatedModel->model()->associate($invoice);
        $actionWithRelatedModel->event = DatabaseActionEvent::CREATED;
        $actionWithRelatedModel->save();

        $this->assertDatabaseHas('database_actions', [
            'author_name' => 'Maria',
            'model_type' => $invoice::class,
            'model_id' => $invoice->id,
            'event' => 'created',
        ]);

        $this->assertInstanceOf(Invoice::class, $actionWithRelatedModel->model);
        $this->assertEquals(45, $actionWithRelatedModel->model->total);
    }
}
