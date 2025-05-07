<?php

namespace Modules\core\app\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\core\app\Traits\ResponderTrait;

class ModelController extends Controller
{
    use ResponderTrait;

    protected $model;
    protected $request;
    protected $fillable;
    protected $requestFillable;


    public function __construct(Request $request,$model)
    {

        $this->model=$model;
        $this->fillable=$this->model->getFillable();
        $this->request=$request;
        $this->requestFillable=$this->request->only($this->fillable);
    }
}
