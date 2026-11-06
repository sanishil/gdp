<?php

namespace App\Models;

use Database\Factories\CoverPrintFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A cover page a student printed, with what was on it. created_at is when it was printed.
 */
#[Fillable(['student_id', 'subject', 'subject_code', 'page_type', 'teacher', 'second_teacher', 'submitted_on'])]
class CoverPrint extends Model
{
    /** @use HasFactory<CoverPrintFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'submitted_on' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
