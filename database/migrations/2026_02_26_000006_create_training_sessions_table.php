<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_group_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('day_of_week');
            $table->string('start_time', 5);
            $table->string('end_time', 5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
