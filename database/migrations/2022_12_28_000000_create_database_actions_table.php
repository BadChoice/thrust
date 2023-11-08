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
        Schema::create('database_actions', function (Blueprint $table) {
            $table->id();
            $table->string('author_name', 100);
            $table->nullableMorphs('author');
            $table->morphs('model');
            $table->enum('event', ['created', 'updated', 'deleted']);
            $table->text('original')->nullable();
            $table->text('current')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('author_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('database_actions');
    }
};
