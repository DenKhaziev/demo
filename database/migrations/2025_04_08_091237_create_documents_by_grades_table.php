<?php

use App\Models\Child;
use App\Models\Grade;
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
        Schema::create('documents_by_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Grade::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignIdFor(Child::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('document_birth')->nullable();
            $table->string('document_application_for_transfer')->nullable(); // заявление на зачисление
            $table->string('document_personal_of_processing_data')->nullable(); // processing of personal data
            $table->string('document_payment')->nullable(); // чек об оплате
            $table->string('document_from_9_graduate')->nullable(); // аттестат за 9 классов для 10 и 11 классов
            $table->string('document_about_transfer')->nullable(); // справка о зачислении
            $table->string('document_certificate_by_grade')->nullable(); // справка об окончании класса с печатью и подписью
            $table->boolean('has_personal_affair')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_by_grades');
    }
};

/* таблица для хранения данных о документах в разрезе уч года
 * 4 файла с 1 - 9 класс (5 для 10-11 классов + аттестат за 9классов)
 * 1 файл - справка о зачислении (печать подпись - скан)
 * 1 файл - справка о прохождении ПА (печать подпись - скан)
 * child_id
 * grade_id
 */
