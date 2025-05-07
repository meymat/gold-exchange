<?php


namespace Modules\core\app\Traits;


trait ResourceWithTrait
{

    public function with($request)
    {
        return ['meta' => [
            'status' => true,
            'message' => 'Done!'
        ]];

    }


}
