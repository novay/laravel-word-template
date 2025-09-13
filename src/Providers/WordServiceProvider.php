<?php

namespace Novay\Word\Providers;

use Illuminate\Support\ServiceProvider;
use Novay\Word\WordManager;
use Novay\Word\Commands\PublishTemplateCommand;

class WordServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/word.php' => config_path('word.php'),
        ], 'config');

        // Publish templates
        $this->publishes([
            __DIR__ . '/../../resources/templates' => resource_path('word-templates'),
        ], 'templates');

        // Register Artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishTemplateCommand::class,
            ]);
        }
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/word.php',
            'word'
        );

        // Register singleton 'word' -> WordManager
        $this->app->singleton('word', function ($app) {
            return new WordManager();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['word'];
    }
}

// composer require btekno/laravel-word-template
// php artisan vendor:publish --tag=config
// php artisan vendor:publish --tag=templates
// php artisan word:publish-templates
