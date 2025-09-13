<?php

namespace YourVendor\LaravelJsonErrors\Http\Middleware;

use Closure;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class JsonExceptionMiddleware
{
    public function handle($request, Closure $next): JsonResponse
    {
        try {
            return $next($request);
        } catch (Throwable $e) {
            return $this->format($e);
        }
    }

    protected function format(Throwable $e): JsonResponse
    {
        $status = $e instanceof HttpExceptionInterface
            ? $e->getStatusCode()
            : ($e instanceof ValidationException ? 422 : 500);

        $error = $e instanceof ValidationException
            ? 'Validation Failed'
            : ($e instanceof HttpExceptionInterface
                ? $e->getMessage() ?: 'HTTP Error'
                : 'Server Error');

        $details = $e instanceof ValidationException
            ? $e->errors()
            : (config('json-errors.debug') ? [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : null);

        return response()->json(array_filter([
            'success' => false,
            'status'  => $status,
            'error'   => $error,
            'details' => $details,
        ]), $status);
    }
}
