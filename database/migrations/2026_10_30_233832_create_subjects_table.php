<?php

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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('semester');
            $table->string('name', 120);
            $table->string('code', 20);
            $table->timestamps();

            $table->unique(['department_id', 'semester', 'name']);
            /* Common courses (Indian Constitution, the first-year subjects, ...) share a code across departments. */
            $table->unique(['department_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
