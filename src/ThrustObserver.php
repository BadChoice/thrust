<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\Models\DatabaseAction;
use BadChoice\Thrust\Models\Enums\DatabaseActionEvent;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ThrustObserver
{
    // https://laravel.com/docs/9.x/eloquent#observers-and-database-transactions
    public bool $afterCommit = true;

    protected bool $enabled = true;
    protected Closure $authorModel;
    protected Closure $authorName;
    protected ResourceManager $manager;

    protected array $overlook = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function __construct()
    {
        $this->setAuthorModelCallback(fn () => auth()->user());
        $this->setAuthorNameCallback(fn ($author) => $author?->email ?? 'Nameless');
        $this->manager = new ResourceManager;
    }

    public function enable(bool $value = true): void
    {
        $this->enabled = $value;
    }

    public function disable(bool $value = true): void
    {
        $this->enabled = ! $value;
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }

    public function register(): void
    {
        if (! $this->enabled) {
            return;
        }
        collect($this->manager->models(observable: true))
            ->each(fn ($model) => $model::observe(static::class));
    }

    public function created(Model $model): void
    {
        $this->mergeOverlookedAttributes($model);

        $attributes = collect($model->getDirty())
            ->reject(fn ($value, $key) => $value === null || $this->overlooked($key));

        if ($attributes->isEmpty()) {
            return;
        }

        $this->trackDatabaseAction(
            model: $model,
            event: DatabaseActionEvent::CREATED,
            original: null,
            current: $attributes,
        );
    }

    public function updated(Model $model): void
    {
        $this->mergeOverlookedAttributes($model);

        $attributes = collect($model->getDirty())
            ->reject(fn ($value, $key) => $this->overlooked($key));

        if ($attributes->isEmpty()) {
            return;
        }

        $this->trackDatabaseAction(
            model: $model,
            event: DatabaseActionEvent::UPDATED,
            original: $attributes->map(fn ($value, $key) => $model->getOriginal($key)),
            current: $attributes,
        );
    }

    public function deleted(Model $model): void
    {
        $this->mergeOverlookedAttributes($model);

        $attributes = collect($model->getAttributes())
            ->reject(fn ($value, $key) => $value === null || $this->overlooked($key));

        if ($attributes->isEmpty()) {
            return;
        }

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

        $attributes = [
            ...$this->author(),
            'model_type' => $model::class,
            'model_id' => $model->id,
            'event' => $event,
            'original' => $original,
            'current' => $current,
            'ip' => request()->ip(),
        ];

        try {
            return DatabaseAction::create($attributes);
        } catch (QueryException $e) {
            // The table is probably not found because the user have not yet
            // logged in, so we don't bother to log anything.
        } catch (Exception $e) {
            Log::error('An exception occurred while trying to create a DatabaseAction', $attributes);
        }
        return null;
    }

    public function setAuthorModelCallback(callable $callback): void
    {
        $this->authorModel = Closure::fromCallable($callback);
    }

    public function setAuthorNameCallback(callable $callback): void
    {
        $this->authorName = Closure::fromCallable($callback);
    }

    protected function author(): array
    {
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return [
                'author_name' => $this->runningCommand(),
            ];
        }

        $author = ($this->authorModel)();

        if (! $author instanceof Model) {
            return [
                'author_name' => 'Unknown',
            ];
        }

        return [
            'author_name' => ($this->authorName)($author),
            'author_type' => $author::class,
            'author_id' => $author->id,
        ];
    }

    protected function runningCommand(): string
    {
        $command = request()->server('argv');
        if (is_array($command)) {
            $command = implode(' ', $command);
        }
        return "php {$command}";
    }

    protected function mergeOverlookedAttributes(Model $model): void
    {
        $key = $this->manager->resourceNameFromModel($model::class);
        $resource = $this->manager->resources()[$key];
        $this->overlook = [
            ...$this->overlook,
            ...(new $resource)->overlooked(),
        ];
    }

    protected function overlooked(string $key): bool
    {
        return in_array($key, $this->overlook);
    }
}
