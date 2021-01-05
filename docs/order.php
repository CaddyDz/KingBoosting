<?php

declare(strict_types=1);

/**
 * @OA\Post(
 ** path="/api/orders",
 *   tags={"Order"},
 *   summary="Place a new order",
 *   operationId="order",
 *
 *   @OA\Parameter(
 *      name="service",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *           type="string"
 *      )
 *   ),
 *   @OA\Parameter(
 *      name="tier",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *          type="string"
 *      )
 *   ),
 *   @OA\Parameter(
 *      name="division",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *          type="string"
 *      )
 *   ),
 *   @OA\Parameter(
 *      name="server",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *          type="string"
 *      )
 *   ),
 *   @OA\Parameter(
 *      name="specific_champions",
 *      in="query",
 *      required=false,
 *      @OA\Schema(
 *          type="boolean"
 *      )
 *   ),
 *   @OA\Parameter(
 *      name="priority",
 *      in="query",
 *      required=false,
 *      @OA\Schema(
 *          type="boolean"
 *      )
 *   ),
 *   @OA\Parameter(
 *      name="streaming",
 *      in="query",
 *      required=false,
 *      @OA\Schema(
 *          type="boolean"
 *      )
 *   ),
 *   @OA\Parameter(
 *      name="price",
 *      in="query",
 *      required=true,
 *      @OA\Schema(
 *          type="integer"
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
