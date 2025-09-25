<?php

use App\Models\Child;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Test;
use App\Models\TestType;
use App\Models\User;
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
        Schema::create('passed_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Child::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Test::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Subject::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Grade::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignIdFor(TestType::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->json('answers'); //  вопросы с ответами
            $table->integer('correct_answers_count')->nullable();
            $table->integer('max_score')->nullable(); // optional
            $table->integer('score')->nullable(); // оценка
            $table->integer('status')->default(0); // 0 - тест пройден, 1 - пересдача (неуд) или пройти еще если демо
            $table->timestamp('passed_at')->useCurrent(); // дата и время
            $table->integer('time_spent')->nullable();
            $table->integer('total_questions');
            $table->integer('attempts_left')->default(10); // if true - (-1)
            $table->SoftDeletes();
            $table->timestamps();
            $table->unique(['child_id', 'test_id', 'subject_id', 'grade_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passed_tests');
    }
};
