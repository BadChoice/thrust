<?php declare(strict_types=1);

namespace BadChoice\Thrust\Models;

use BadChoice\Thrust\Models\Enums\HistoryTrackEvent;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class HistoryTrack extends Model
{
    use HasUlids;

    public $timestamps = false;

    protected $guarded = ['id', 'created_at'];

    protected $casts = [
        'event' => HistoryTrackEvent::class,
        'old' => AsCollection::class,
        'new' => AsCollection::class,
        'created_at' => 'immutable_datetime',
    ];

    protected static function booted()
    {
        static::updating(function (HistoryTrack $historyTrack) {
            throw new LogicException("Failed to update the HistoryTrack with ID {$historyTrack->id} because this model cannot be updated");
        });
    }
}
