<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('history_tracks')) {
            return;
        }
        Schema::create('history_tracks', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('user_name', 100);
            $table->unsignedBigInteger('user_id');
            $table->morphs('model');
            $table->enum('event', ['created', 'updated', 'deleted']);
            $table->text('old')->nullable();
            $table->text('new')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->primary('id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_tracks');
    }
};
