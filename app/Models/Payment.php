<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * A Razorpay order for one cover page. The page's details wait in print_details until the payment is captured,
 * whether the browser confirms it first or Razorpay's webhook does; either way the cover print is saved only once.
 */
#[Fillable(['student_id', 'cover_print_id', 'razorpay_order_id', 'razorpay_payment_id', 'amount', 'currency', 'status', 'print_details', 'paid_at'])]
class Payment extends Model
{
    /** @use HasFactory<PaymentFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'status' => PaymentStatus::class,
            'print_details' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return BelongsTo<CoverPrint, $this>
     */
    public function coverPrint(): BelongsTo
    {
        return $this->belongsTo(CoverPrint::class);
    }

    /**
     * Mark the order paid and save its cover print. Safe to call more than once (the browser and the webhook both
     * do): the row is locked, and an order that is already paid is left as it is.
     */
    public function markPaid(string $razorpayPaymentId): void
    {
        DB::transaction(function () use ($razorpayPaymentId): void {
            $payment = static::whereKey($this->getKey())->lockForUpdate()->firstOrFail();

            if ($payment->status !== PaymentStatus::Paid) {
                $print = $payment->student->prints()->create($payment->print_details);

                $payment->update([
                    'status' => PaymentStatus::Paid,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'cover_print_id' => $print->id,
                    'paid_at' => now(),
                ]);
            }

            $this->setRawAttributes($payment->getAttributes(), true);
        });
    }

    /**
     * Record a failed attempt, unless the order has been paid since.
     */
    public function markFailed(string $razorpayPaymentId): void
    {
        static::whereKey($this->getKey())
            ->where('status', '!=', PaymentStatus::Paid)
            ->update(['status' => PaymentStatus::Failed, 'razorpay_payment_id' => $razorpayPaymentId]);

        $this->refresh();
    }

    /**
     * The amount in rupees, e.g. "₹10" or "₹12.50".
     */
    public function formattedAmount(): string
    {
        return self::formatPaise($this->amount);
    }

    public static function formatPaise(int $paise): string
    {
        return '₹'.($paise % 100 === 0 ? number_format($paise / 100) : number_format($paise / 100, 2));
    }
}
