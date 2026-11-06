<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| A teacher can hold several designations (e.g. Lecturer (SG-I) and Principal (i/c)).
*/
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('designation_teacher', function (Blueprint $table) {
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('designation_id')->constrained()->restrictOnDelete();

            $table->primary(['teacher_id', 'designation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designation_teacher');
    }
};
