<?php

namespace Modules\core\app\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *     version="1.0",
 *     title="Example for response examples value"
 * )
 *
 * @OA\PathItem(path="/api/v1")
 *
 * @OA\SecurityScheme(
 *     description="Api Key for authorization.",
 *     securityScheme="Authorization",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization"
 *   )
 *
 */
class CoreController extends BaseController
{


}
