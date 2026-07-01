<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();

           
            $table->string('number');
            $table->string('name')->nullable();
            $table->unsignedTinyInteger('floor')->nullable();
            $table->unsignedTinyInteger('capacity');

            $table->enum('type', ['private', 'shared'])->default('shared');

            $table->enum('status', [
                'available',
                'maintenance',
                'inactive',
            ])->default('available');

            $table->timestamps();

            $table->unique(['institution_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
