<?php

namespace Modules\core\app\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ResponderTrait
{

    protected function successResponse($message= 'Done',$data=null)
    {

        return response()->json([
            'data'=>$data,
            'meta' => [
                'status' => true,
                'message' => $message
            ]

        ], 200);
    }

    protected function tokenResponse($token,$user)
    {
        return response()->json([
            'data'=>[
                'token'=>$token,
                'user'=> $user,
            ],
            'meta' => [
                'status' => true,
                'message' => 'Done!'
            ]

        ], 200);
    }

    protected function failedResponse($message, int $code,array $errors=null)
    {

        if ($errors==null){
            $errors=new \stdClass();
        }
        return response()->json([
            'data'=>null,
            'meta' => [
                'status' => false,
                'message' => $message,
                'errors'=>$errors
            ]

        ], $code);
    }

    /**
     * @param $errors
     * @return JsonResponse
     */
    private function failedValidationResponse($errors)
    {
        return $this->failedResponse('خطا در مقادیر ارسال شده', 422,$errors);

    }

    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return $this->failedResponse('خطا در مقادیر ارسال شده', 422,$errors);

    }
}
