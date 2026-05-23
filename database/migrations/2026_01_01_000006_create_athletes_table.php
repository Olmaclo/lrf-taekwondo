<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('athletes', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('birth_place')->nullable();
            $table->enum('gender', ['M', 'F']);
            $table->string('nationality')->default('Sénégalaise');
            $table->string('photo')->nullable();

            // Taekwondo classification
            $table->decimal('weight', 5, 1)->nullable(); // weight in kg
            $table->enum('age_category', ['Benjamin', 'Minime', 'Cadet', 'Junior', 'Senior'])->nullable();
            $table->string('weight_category')->nullable(); // e.g. -54kg, +87kg
            $table->string('club');
            $table->string('license_number')->nullable();

            // Poomsae specific
            $table->string('current_grade')->nullable();  // grade actuel (ex: ceinture noire 1er dan)
            $table->string('target_grade')->nullable();   // grade visé
            $table->integer('years_practice')->nullable();
            $table->date('last_grade_date')->nullable();
            $table->string('master_name')->nullable();

            // Registration
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('coach_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('registration_status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Payment
            $table->enum('payment_status', ['unpaid', 'temp_validated', 'paid', 'validated'])->default('unpaid');
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->string('payment_method')->nullable();   // cash, transfer, mobile_money, check
            $table->string('transaction_ref')->nullable();
            $table->string('receipt_number')->nullable()->unique();
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('temp_validation_deadline')->nullable();
            $table->text('temp_validation_notes')->nullable();
            $table->foreignId('temp_validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('temp_validated_at')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_id', 'registration_status']);
            $table->index(['event_id', 'age_category', 'gender', 'weight_category']);
            $table->index(['club', 'event_id']);
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
