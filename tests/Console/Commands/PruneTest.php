<?php declare(strict_types=1);

namespace Tests\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

final class PruneTest extends TestCase
{
    public function testItPrunesDatabaseActionsOlderThanFifteenDays(): void
    {
        $this
            ->seedDatabase(days: 30)
            ->artisan('thrust:prune')
            ->assertSuccessful();

        $this->assertDatabaseCount('database_actions', 15);
    }

    public function testItPrunesDatabaseActionsOlderThanSpecifiedDays(): void
    {
        $this
            ->seedDatabase(days: 20)
            ->artisan('thrust:prune', ['--days' => 5])
            ->assertSuccessful();

        $this->assertDatabaseCount('database_actions', 5);
    }

    private function seedDatabase(int $days): self
    {
        // We cannot create the records with Eloquent because we want to force
        // the value of the the created_at column and "travel" does not work as
        // this timestamp is set at database layer.
        DB::table('database_actions')->insert(
            collect(range(0, $days - 1))->map(fn (int $days) => [
                'id' => Str::random(20),
                'author_name' => 'Joan',
                'model_type' => 'App\\Models\\Invoice',
                'model_id' => 25,
                'event' => 'updated',
                'created_at' => now()->subDays($days)->startOfDay(),
            ])->all()
        );

        return $this->assertDatabaseCount('database_actions', $days);
    }
}
