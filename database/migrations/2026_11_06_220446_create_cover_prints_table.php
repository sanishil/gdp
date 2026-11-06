<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| One row per cover page printed: what was on it, kept as printed (names, not IDs), so later edits in the portals
| don't rewrite history. created_at is when it was printed.
*/
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cover_prints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('subject', 150)->nullable();
            $table->string('subject_code', 30)->nullable();
            $table->string('page_type', 100)->nullable();
            $table->string('teacher', 100)->nullable();
            $table->string('second_teacher', 100)->nullable();
            $table->date('submitted_on')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cover_prints');
    }
};
