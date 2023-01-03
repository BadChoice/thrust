<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\Models\DatabaseAction;
use BadChoice\Thrust\Models\Enums\DatabaseActionEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Closure;

class ThrustObserver
{
    protected bool $enabled = true;

    protected Closure $authorName;

    protected array $ignore = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function enable(bool $value = true): void
    {
        $this->enabled = $value;
    }

    public function disable(bool $value = true): void
    {
        $this->enabled = ! $value;
    }

    public function register(): void
    {
        if (! $this->enabled) {
            return;
        }
        $models = (new ResourceManager)->models(observable: true);
        collect($models)->each(fn ($model) => $model::observe(static::class));
    }

    public function created(Model $model): void
    {
        $attributes = collect($model->getDirty())
            ->reject(fn ($value, $key) => $value === null || $this->ignored($key));

        $this->trackDatabaseAction(
            model: $model,
            event: DatabaseActionEvent::CREATED,
            original: null,
            current: $attributes,
        );
    }

    public function updated(Model $model): void
    {
        $attributes = collect($model->getDirty())
            ->reject(fn ($value, $key) => $this->ignored($key));

        $this->trackDatabaseAction(
            model: $model,
            event: DatabaseActionEvent::UPDATED,
            original: $attributes->map(fn ($value, $key) => $model->getOriginal($key)),
            current: $attributes,
        );
    }

    public function deleted(Model $model): void
    {
        $attributes = collect($model->getAttributes())
            ->reject(fn ($value, $key) => $value === null || $this->ignored($key));

        $this->trackDatabaseAction(
            model: $model,
            event: DatabaseActionEvent::DELETED,
            original: $attributes,
            current: null,
        );
    }

    protected function trackDatabaseAction(
        Model $model,
        DatabaseActionEvent $event,
        ?Collection $original,
        ?Collection $current,
    ): ?DatabaseAction {
        if (! $this->enabled) {
            return null;
        }

        return DatabaseAction::create([
            ...$this->author(),
            'model_type' => $model::class,
            'model_id' => $model->id,
            'event' => $event,
            'original' => $original,
            'current' => $current,
            'ip' => request()->ip(),
        ]);
    }

    public function setAuthorNameCallback(callable $callback): void
    {
        $this->authorName = Closure::fromCallable($callback);
    }

    protected function author(): array
    {
        if (! auth()->check()) {
            return [
                'author_name' => 'Unknown', // stateless (probably from the API)
            ];
        }

        $user = auth()->user();
        $getAuthorName = isset($this->authorName)
            ? $this->authorName
            : fn () => 'Nameless';

        return [
            'author_name' => $getAuthorName($user),
            'author_type' => $user::class,
            'author_id' => $user->id,
        ];
    }

    protected function ignored(string $key): bool
    {
        return in_array($key, $this->ignore);
    }
}
