<?php

use App\Models\Grade;
use App\Models\Subject;
use App\Models\TestType;
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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TestType::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Subject::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Grade::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
// запрет удаление если есть связанные таблицы
//            $table->foreignIdFor(TestType::class)
//                ->constrained()
//                ->cascadeOnUpdate()
//                ->restrictOnDelete();

            $table->integer('allotted_time')->default(60);
            $table->integer('number_of_attempts')->default(10);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
