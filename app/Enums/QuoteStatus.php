<?php

namespace App\Enums;

enum QuoteStatus: string
{
    case Pending = 'pending';
    case Reviewing = 'reviewing';
    case Quoted = 'quoted';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Expired = 'expired';
    case Converted = 'converted';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Reviewing => 'Reviewing',
            self::Quoted => 'Quoted',
            self::Accepted => 'Accepted',
            self::Rejected => 'Rejected',
            self::Expired => 'Expired',
            self::Converted => 'Converted',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Reviewing => 'blue',
            self::Quoted => 'indigo',
            self::Accepted => 'green',
            self::Rejected => 'red',
            self::Expired => 'gray',
            self::Converted => 'emerald',
            self::Cancelled => 'red',
        };
    }
}
