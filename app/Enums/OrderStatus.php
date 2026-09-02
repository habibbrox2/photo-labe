<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Paid = 'paid';
    case Processing = 'processing';
    case QualityCheck = 'quality_check';
    case Revision = 'revision';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::Paid => 'Paid',
            self::Processing => 'Processing',
            self::QualityCheck => 'Quality Check',
            self::Revision => 'Revision',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Confirmed => 'blue',
            self::Paid => 'green',
            self::Processing => 'indigo',
            self::QualityCheck => 'purple',
            self::Revision => 'orange',
            self::Completed => 'emerald',
            self::Cancelled => 'red',
        };
    }

    public function isEditable(): bool
    {
        return !in_array($this, [self::Completed, self::Cancelled]);
    }
}
