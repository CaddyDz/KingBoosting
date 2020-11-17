<?php

declare(strict_types=1);

/**
 * @OA\Get(
 ** path="/api/user",
 *   tags={"User"},
 *   summary="Fetch info of authenticated user",
 *   operationId="user",
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
