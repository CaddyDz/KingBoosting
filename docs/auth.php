<?php

declare(strict_types=1);

/**
 * @OA\Post(
 ** path="/api/login",
 *   tags={"Login"},
 *   summary="Generate a token for user",
 *   operationId="login",
 *
 *   @OA\Parameter(
 *      name="email",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *           type="string"
 *      )
 *   ),
 *   @OA\Parameter(
 *      name="password",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *          type="string"
 *      )
 *   ),
 *   @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *           mediaType="application/json",
 *      )
 *   ),
 *   @OA\Response(
 *      response=403,
 *      description="Forbidden"
 *   )
 *)
 **/

/**
 * @OA\Post(
 ** path="/api/logout",
 *   tags={"Logout"},
 *   summary="Revoke token of user",
 *   operationId="logout",
 *
 *   @OA\Response(
 *      response=200,
 *      description="Success",
 *      @OA\MediaType(
 *           mediaType="application/json",
 *      )
 *   ),
 *   @OA\Response(
 *      response=401,
 *      description="Unauthenticated"
 *   )
 *)
 **/
