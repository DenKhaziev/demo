<?php

use App\Models\AdditionalQuestion;
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
        Schema::create('additional_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AdditionalQuestion::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('answer');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_answers');
    }
};
