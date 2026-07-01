<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('patient_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('diagnosis');
            $table->string('cie10_code', 10)->nullable();
            $table->string('cie10_label')->nullable();
            $table->text('notes')->nullable();
            $table->date('diagnosed_at');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('patient_diagnoses'); }
};
