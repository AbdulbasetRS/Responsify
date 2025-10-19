<?php

namespace Abdulbaset\Responsify\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Responsify Service Provider for Laravel
 *
 * This service provider handles the registration and bootstrapping of the Responsify package.
 */
class ResponsifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge package configuration with application's configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/responsify.php',
            'responsify'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../Config/responsify.php' => config_path('responsify.php'),
            ], 'responsify-config');

            // Publish language files
            $this->publishes([
                __DIR__ . '/../lang' => lang_path('vendor/responsify'),
            ], 'responsify-lang');
        }
    }
}
