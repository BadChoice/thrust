<?php declare(strict_types=1);

namespace BadChoice\Thrust\Models;

use BadChoice\Thrust\Models\Enums\DatabaseActionEvent;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use LogicException;

class DatabaseAction extends Model
{
    public $timestamps = false;

    protected $guarded = ['created_at'];

    protected $casts = [
        'event' => DatabaseActionEvent::class,
        'original' => AsCollection::class,
        'current' => AsCollection::class,
        'created_at' => 'immutable_datetime',
    ];

    protected static function booted()
    {
        static::updating(function (DatabaseAction $action) {
            throw new LogicException("Failed to update the DatabaseAction with ID {$action->id} because this model cannot be updated");
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

    protected function setAuthorNameAttribute(mixed $value): void
    {
        $this->attributes['author_name'] = mb_substr((string) $value, 0, 100);
    }

    protected function setOriginalAttribute(mixed $value): void
    {
        $this->attributes['original'] = $this->preventDoubleEncoding($value);
    }

    protected function setCurrentAttribute(mixed $value): void
    {
        $this->attributes['current'] = $this->preventDoubleEncoding($value);
    }

    protected function preventDoubleEncoding(mixed $attributes): ?Collection
    {
        if ($attributes === null) {
            return null;
        }

        return collect($attributes)->map(function (mixed $value): mixed {
            if (! is_string($value)) {
                return $value;
            }

            $decoded = json_decode($value);

            return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
        });
    }
}
