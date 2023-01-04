<?php declare(strict_types=1);

namespace BadChoice\Thrust\Models;

use BadChoice\Thrust\Models\Enums\DatabaseActionEvent;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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

    protected function authorName(): Attribute
    {
        return Attribute::make(
            set: fn (mixed $value) => mb_substr((string) $value, 0, 100),
        );
    }
}
