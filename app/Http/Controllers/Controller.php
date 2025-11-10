<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    /**
 * @OA\OpenApi(
 *   @OA\Info(
 *       title="API Documentation",
 *       version="1.0.0",
 *       description="Documentation de l'API générée avec Swagger"
 *   )
 * )
 */
}
