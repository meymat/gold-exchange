<?php

namespace Modules\commission\app\Models;

use Carbon\Carbon;
use Modules\core\app\Models\BaseModel;

/**
 * @property int id
 * @property float from_amount
 * @property float to_amount
 * @property float percentage
 * @property integer minimum_fee
 * @property integer maximum_fee
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class CommissionRule extends BaseModel
{
    protected $fillable = [
        "from_amount",
        "to_amount",
        "percentage",
        "minimum_fee",
        "maximum_fee",
    ];
}
