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
        ], 'word-config');

        // Publish templates
        $this->publishes([
            __DIR__ . '/../../examples' => storage_path('app/word'),
        ], 'word-templates');

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