<?php declare(strict_types=1);

namespace Tests\Models;

use App\Models\Employee;
use App\Models\Invoice;
use BadChoice\Thrust\Models\Enums\HistoryTrackEvent;
use BadChoice\Thrust\Models\HistoryTrack;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use LogicException;
use Tests\TestCase;

final class HistoryTrackTest extends TestCase
{
    public function testItIsPersistedAndCasted(): void
    {
        HistoryTrack::create([
            'author_name' => 'Joan',
            'author_type' => 'App\\Models\\Employee',
            'author_id' => 32,
            'model_type' => 'App\\Models\\Invoice',
            'model_id' => 25,
            'event' => HistoryTrackEvent::UPDATED,
            'old' => ['total' => 45],
            'new' => ['total' => 60],
            'ip' => '215.6.18.147',
        ]);

        $this->assertDatabaseCount('history_tracks', 1);

        $historyTrack = HistoryTrack::first();

        $this->assertMatchesRegularExpression('/^[0-9A-Za-z]{26}$/', $historyTrack->id, 'The ID is not a valid ULID');
        $this->assertEquals('Joan', $historyTrack->author_name);
        $this->assertEquals('App\\Models\\Employee', $historyTrack->author_type);
        $this->assertEquals(32, $historyTrack->author_id);
        $this->assertEquals('App\\Models\\Invoice', $historyTrack->model_type);
        $this->assertEquals(25, $historyTrack->model_id);
        $this->assertInstanceOf(HistoryTrackEvent::class, $historyTrack->event, 'The EVENT attribute is not an Enum');
        $this->assertEquals(HistoryTrackEvent::UPDATED, $historyTrack->event);
        $this->assertInstanceOf(Collection::class, $historyTrack->old, 'The OLD attribute is not a Collection');
        $this->assertEquals(45, $historyTrack->old->get('total'));
        $this->assertInstanceOf(Collection::class, $historyTrack->new, 'The NEW attribute is not a Collection');
        $this->assertEquals(60, $historyTrack->new->get('total'));
        $this->assertEquals('215.6.18.147', $historyTrack->ip);
        $this->assertInstanceOf(CarbonImmutable::class, $historyTrack->created_at, 'The CREATED_AT attribute is not an Immutable Datetime');
    }

    public function testItIsImmutable(): void
    {
        $historyTrack = HistoryTrack::create([
            'author_name' => 'Joan',
            'model_type' => 'App\\Models\\Invoice',
            'model_id' => 25,
            'event' => HistoryTrackEvent::UPDATED,
        ]);

        try {
            $historyTrack->update(['author_name' => 'Josep']);
        } catch(LogicException $e) {
            $this->assertStringContainsString('cannot be updated', $e->getMessage());
        }

        $this->assertDatabaseMissing('history_tracks', ['author_name' => 'Josep']);
    }

    public function testItMayHaveARelatedAuthor(): void
    {
        $historyTrackWithoutRelatedAuthor = HistoryTrack::create([
            'author_name' => 'Joan',
            'model_type' => 'App\\Models\\Invoice',
            'model_id' => 25,
            'event' => HistoryTrackEvent::UPDATED,
        ]);

        $this->assertNull($historyTrackWithoutRelatedAuthor->author);

        $author = Employee::create([
            'age' => 22,
        ]);

        $historyTrackWithRelatedAuthor = new HistoryTrack();
        $historyTrackWithRelatedAuthor->author_name = 'Maria';
        $historyTrackWithRelatedAuthor->author()->associate($author);
        $historyTrackWithRelatedAuthor->model_type = 'App\\Models\\Invoice';
        $historyTrackWithRelatedAuthor->model_id = 25;
        $historyTrackWithRelatedAuthor->event = HistoryTrackEvent::CREATED;
        $historyTrackWithRelatedAuthor->save();

        $this->assertDatabaseHas('history_tracks', [
            'author_name' => 'Maria',
            'author_type' => $author::class,
            'author_id' => $author->id,
            'event' => 'created',
        ]);

        $this->assertInstanceOf(Employee::class, $historyTrackWithRelatedAuthor->author);
        $this->assertEquals(22, $historyTrackWithRelatedAuthor->author->age);
    }

    public function testItMayHaveARelatedModel(): void
    {
        $historyTrackWithoutRelatedModel = HistoryTrack::create([
            'author_name' => 'Joan',
            'model_type' => 'App\\Models\\Post',
            'model_id' => 25,
            'event' => HistoryTrackEvent::UPDATED,
        ]);

        $this->assertNull($historyTrackWithoutRelatedModel->model);

        $invoice = Invoice::create([
            'total' => 45,
        ]);

        $historyTrackWithRelatedModel = new HistoryTrack();
        $historyTrackWithRelatedModel->author_name = 'Maria';
        $historyTrackWithRelatedModel->model()->associate($invoice);
        $historyTrackWithRelatedModel->event = HistoryTrackEvent::CREATED;
        $historyTrackWithRelatedModel->save();

        $this->assertDatabaseHas('history_tracks', [
            'author_name' => 'Maria',
            'model_type' => $invoice::class,
            'model_id' => $invoice->id,
            'event' => 'created',
        ]);

        $this->assertInstanceOf(Invoice::class, $historyTrackWithRelatedModel->model);
        $this->assertEquals(45, $historyTrackWithRelatedModel->model->total);
    }
}
