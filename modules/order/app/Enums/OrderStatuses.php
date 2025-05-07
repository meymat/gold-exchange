<?php

namespace Modules\order\app\Enums;

enum OrderStatuses: string {
    case Open = 'open';
    case PartiallyFilled = 'partially_filled';
    case Filled = 'filled';
    case Cancelled = 'cancelled';
}
