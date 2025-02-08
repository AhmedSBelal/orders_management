<?php

namespace App\Enums;

enum OrderStatuses: string
{
    case InProcessing = 'تحت التجهيز';
    case InFactory = 'فى المصنع';
    case Prepared = 'تم التجهيز';
    case Shipped = 'تم الشحن';
    case Returned = 'مرتجع';
    case Cancelled = 'الغاء';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
