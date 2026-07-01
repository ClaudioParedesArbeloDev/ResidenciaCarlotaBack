<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('patient_evolutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['medical','nursing','kinesiology','nutrition','social','general'])->default('general');
            $table->text('evolution');
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('patient_evolutions'); }
};
