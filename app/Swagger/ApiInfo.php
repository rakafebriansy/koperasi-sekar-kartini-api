<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Koperasi Sekar Kartini API",
 *     description="Documentation API Koperasi Sekar Kartini",
 *     @OA\Contact(
 *         email="support@koperasisekarkartini.com",
 *         name="API Support"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Use your API token as Bearer token for authorization"
 * )
 */
class ApiInfo
{
}

