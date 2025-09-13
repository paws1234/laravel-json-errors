<?php

return [
    'format' => [
        'success' => false,
        'status' => null,
        'error' => null,
        'details' => null,
    ],

    'map' => [
        Illuminate\Validation\ValidationException::class => 422,
        Illuminate\Auth\AuthenticationException::class => 401,
        Illuminate\Auth\Access\AuthorizationException::class => 403,
        Illuminate\Database\Eloquent\ModelNotFoundException::class => 404,
        Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => 404,
        Exception::class => 500,
    ],

    // Should the package expose detailed errors in local?
    'debug' => env('APP_DEBUG', false),
];
