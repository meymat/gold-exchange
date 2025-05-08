<?php

namespace Modules\order\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\core\app\Http\Models\BaseModel;
use Modules\core\app\Traits\ModelsTrait\GeneralCrudTrait;
use Modules\trade\app\Models\Trade;
use Modules\user\app\Models\User;

/**
 * @property int id
 * @property int user_id
 * @property string trade_type
 * @property integer price
 * @property float initial_quantity
 * @property float remaining_quantity
 * @property string order_status
 * @property Carbon executed_at
 * @property Carbon cancelled_at
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Order extends BaseModel
{
    use GeneralCrudTrait;

    protected $fillable = [
        "user_id",
        "trade_type",
        "price",
        "initial_quantity",
        "remaining_quantity",
        "order_status",
        "executed_at",
        "cancelled_at",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class, 'order_id', 'id');
    }

}
