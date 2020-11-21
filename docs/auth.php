<?php

declare(strict_types=1);

/**
 * @OA\Post(
 *     path="/api/auth/login",
 *     tags={"Login"},
 *     summary="Generate a token for user",
 *     operationId="login",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *                 example={"email": "user@kingboosting.com", "password": "password"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     ),
 *     @OA\Response(
 *      response=403,
 *      description="Forbidden"
 *     )
 * )
 */
