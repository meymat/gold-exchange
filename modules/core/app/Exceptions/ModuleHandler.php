<?php

namespace Modules\core\app\Exceptions;

use App\Exceptions\Handler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Modules\core\app\Traits\ResponderTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModuleHandler extends Handler
{
    use ResponderTrait;
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        parent::register();

        $this->renderable(function (NotFoundHttpException $e) {
            return $this->failedResponse(__('Not Found'),404);
        });

        $this->renderable(function (AuthenticationException $e) {
            return $this->failedResponse(__('Unauthorized'),403);
        });

        $this->renderable(function (ThrottleRequestsException $e) {
            return $this->failedResponse(__('Too Many Requests'),429);
        });
    }
}
