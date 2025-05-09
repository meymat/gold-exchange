<?php

namespace Modules\core\app\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\core\app\Traits\ModelsTrait\GeneralCrudTrait;

class BaseModel extends Model
{
    use GeneralCrudTrait;
    public $cacheable = false;
    public $cacheMinutes = 1440;
}
