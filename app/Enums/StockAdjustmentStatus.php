<?php

namespace App\Enums;

enum StockAdjustmentStatus: string
{
    case Draft = 'draft';
    case OnProcess = 'on_process';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::OnProcess => 'On process',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function canTransitionTo(self $to): bool
    {
        return match ($this) {
            self::Draft => $to === self::OnProcess,
            self::OnProcess => in_array($to, [self::Completed, self::Cancelled], true),
            self::Completed, self::Cancelled => false,
        };
    }
}
