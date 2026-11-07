<?php

namespace App\Enums;

/**
 * A help ticket is open until an admin marks it resolved.
 */
enum TicketStatus: string
{
    case Open = 'open';
    case Resolved = 'resolved';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Resolved => 'Resolved',
        };
    }
}
