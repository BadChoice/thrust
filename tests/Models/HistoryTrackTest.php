<?php declare(strict_types=1);

namespace Tests\Models;

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
            'user_name' => 'Joan',
            'user_id' => 5,
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
        $this->assertEquals('Joan', $historyTrack->user_name);
        $this->assertEquals(5, $historyTrack->user_id);
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
            'user_name' => 'Joan',
            'user_id' => 5,
            'model_type' => 'App\\Models\\Invoice',
            'model_id' => 25,
            'event' => HistoryTrackEvent::UPDATED,
        ]);

        try {
            $historyTrack->update(['user_name' => 'Josep']);
        } catch(LogicException $e) {
            $this->assertStringContainsString('cannot be updated', $e->getMessage());
        }

        $this->assertDatabaseMissing('history_tracks', ['user_name' => 'Josep']);
    }
}
