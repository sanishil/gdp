<?php

namespace App\Enums;

/**
 * What a help ticket is about, chosen on the Help page.
 */
enum TicketCategory: string
{
    case Payment = 'payment';
    case Refund = 'refund';
    case Account = 'account';
    case CoverPage = 'cover_page';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Payment => 'Payment issue',
            self::Refund => 'Refund request',
            self::Account => 'Account or sign-in',
            self::CoverPage => 'Cover page problem',
            self::Other => 'Something else',
        };
    }

    /**
     * Payment and refund tickets need the transaction ID to be traced.
     */
    public function needsTransactionId(): bool
    {
        return in_array($this, [self::Payment, self::Refund], true);
    }
}
