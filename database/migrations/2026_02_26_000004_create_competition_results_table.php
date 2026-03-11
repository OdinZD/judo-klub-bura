<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('athlete_name');
            $table->string('weight_category')->nullable();
            $table->string('placement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_results');
    }
};
