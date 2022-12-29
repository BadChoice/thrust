<?php declare(strict_types=1);

namespace BadChoice\Thrust\Models;

use BadChoice\Thrust\Models\Enums\HistoryTrackEvent;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function __get($key)
    {
        if (in_array($key, ['author', 'model']) && ! $this->morphedModelExists($key)) {
            return null;
        }
        return parent::__get($key);
    }

    protected function morphedModelExists(string $key): bool
    {
        $class = $this->getAttributeFromArray("{$key}_type");

        return $class && class_exists($class);
    }
}
