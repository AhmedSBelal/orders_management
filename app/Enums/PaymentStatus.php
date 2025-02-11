<?php

namespace App\Enums;

enum PaymentStatus : string
{
    case Paid = 'تم الدفع';
    case WaitPaid = 'انتظار الدفع';
    case NotPaid = 'لم يتم الدفع';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
