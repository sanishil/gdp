<?php

namespace App\Models;

use App\Enums\TicketCategory;
use App\Enums\TicketStatus;
use Database\Factories\SupportTicketFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * A help ticket raised from the Help page, e.g. a payment that went through but didn't work, or a refund request.
 */
#[Fillable(['user_id', 'name', 'email', 'category', 'transaction_id', 'message', 'screenshot_path'])]
class SupportTicket extends Model
{
    /** @use HasFactory<SupportTicketFactory> */
    use HasFactory;

    /**
     * Screenshots live on the private local disk under this folder.
     */
    public const SCREENSHOT_FOLDER = 'ticket-screenshots';

    protected static function booted(): void
    {
        static::deleted(function (SupportTicket $ticket): void {
            if ($ticket->screenshot_path) {
                Storage::disk('local')->delete($ticket->screenshot_path);
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'category' => TicketCategory::class,
            'status' => TicketStatus::class,
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function attributes(): array
    {
        return ['status' => TicketStatus::Open->value];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The number people quote, e.g. "GDP0012".
     *
     * @return Attribute<string, never>
     */
    protected function reference(): Attribute
    {
        return Attribute::get(fn (): string => 'GDP'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT));
    }
}
