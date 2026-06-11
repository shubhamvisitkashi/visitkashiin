<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\NullHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        // PHP 8.1+ deprecations from Laravel 8's bundled packages (e.g.
        // SwiftMailer's "callable of the form [Obj, 'Parent::method']")
        // get logged via the "deprecations" channel by HandleExceptions.
        // Force it to a null handler here so it can't be re-enabled by
        // a stale .env / cached config on the server, regardless of
        // what LOG_DEPRECATIONS_CHANNEL resolves to.
        config([
            'logging.channels.null' => config('logging.channels.null') ?? [
                'driver' => 'monolog',
                'handler' => NullHandler::class,
            ],
            'logging.deprecations' => 'null',
        ]);
    }
}
