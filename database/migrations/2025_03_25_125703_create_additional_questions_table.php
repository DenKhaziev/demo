<?php

use App\Models\Test;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('additional_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Test::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('question');
            $table->boolean('has_image')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_questions');
    }
};
