<?php
namespace Paws1234\LaravelJsonErrors\Tests;

use Orchestra\Testbench\TestCase;
use Paws1234\LaravelJsonErrors\JsonErrorsServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class JsonErrorTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [JsonErrorsServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app['router']->aliasMiddleware('json.errors', \Paws1234\LaravelJsonErrors\Http\Middleware\JsonExceptionMiddleware::class);
    }

    /** @test */
    public function it_returns_json_on_validation_exception()
    {
        Route::get('/test-validation', function () {
            throw ValidationException::withMessages([
                'email' => ['The email field is required.'],
            ]);
        })->middleware('json.errors');

        $response = $this->getJson('/test-validation');

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'success', 'status', 'error', 'details'
        ]);
    }
}

