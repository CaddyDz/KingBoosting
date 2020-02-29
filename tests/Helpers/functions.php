<?php

function create($model, $properties = [], $method = 'create', $times = null)
{
    return factory($model, $times)->$method($properties);
}

function sanitize($response)
{
    $response = (object) json_decode($response->getContent(), true);
    return $response;
}
