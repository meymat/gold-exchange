<?php

namespace Modules\wallet\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\core\app\Models\BaseModel;
use Modules\user\app\Models\User;

/**
 * @property int id
 * @property int user_id
 * @property string gold_balance
 * @property integer amount_balance
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Wallet extends BaseModel
{
    protected $fillable = [
        "user_id",
        "gold_balance",
        "amount_balance",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
