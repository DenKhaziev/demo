<?php

use App\Models\Child;
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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            // Связи
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Child::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            // Свидетельство о рождении ребенка
            $table->date('child_birth_date')->nullable();
            $table->string('child_birth_cert_number')->nullable();
            $table->date('child_birth_cert_issued_at')->nullable();
            $table->string('child_birth_cert_issued_by')->nullable(); // кем выдано

            // Паспорт родителя / законного представителя
            $table->date('parent_birth_date')->nullable();
            $table->string('parent_passport_series')->nullable();
            $table->string('parent_passport_number')->nullable();
            $table->date('parent_passport_issued_at')->nullable();
            $table->string('parent_passport_issued_by')->nullable(); // кем выдано
            $table->string('parent_passport_department_code')->nullable();
            $table->string('parent_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};

/* все графы из данных о ребенке и родителе + даты + даты регистраций в ЛК + данные родителя и ребенка  -
* user_id, child_id(is_payment)
 * grade_id
 * N_safronova@bk.ru
*/
