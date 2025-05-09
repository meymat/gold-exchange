<?php

namespace Modules\trade\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\core\app\Http\Models\BaseModel;
use Modules\order\app\Models\Order;

/**
 * @property int id
 * @property int buy_order_id
 * @property int sell_order_id
 * @property integer price
 * @property float quantity
 * @property integer commission_buyer
 * @property integer commission_seller
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Trade extends BaseModel
{
    protected $fillable = [
        "id",
        "buy_order_id",
        "sell_order_id",
        "price",
        "quantity",
        "commission_buyer",
        "commission_seller",
    ];

    public function buyOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'buy_order_id', 'id');
    }

    public function sellOrder(): HasMany
    {
        return $this->hasMany(Order::class, 'sell_order_id', 'id');
    }
}
