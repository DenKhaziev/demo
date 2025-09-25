<?php

use App\Models\Grade;
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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Grade::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('password');
            $table->string('email');
            $table->string('attestation_type')->default('ПА');
            $table->boolean('is_has_a_certificate')->default(false);
            $table->boolean('is_payment')->default(false); // не оплачено, оплачено - true
            $table->timestamp('payment_date')->nullable();
//            $table->string('doc_birth')->nullable();
//            $table->string('doc_application')->nullable(); // заявление на зачисление
//            $table->string('doc_POPD')->nullable(); // processing of personal data
//            $table->string('doc_payment')->nullable(); // processing of personal data
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
