<?php

namespace Riclep\StoryblokEmbed;

use Illuminate\Support\ServiceProvider;

class StoryblokEmbedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
		$this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-storyblok-embed');

	    if ($this->app->runningInConsole()) {
		    $this->publishes([
			    __DIR__.'/../config/storyblok-embed.php' => config_path('storyblok-embed.php'),
			    __DIR__.'/resources/views' => resource_path('views/vendor/storyblok-embed'),
		    ], 'storyblok-embed');
	    }
    }

	/**
	 * Register the application services.
	 */
	public function register(): void
	{
		// Automatically apply the package configuration
		$this->mergeConfigFrom(__DIR__ . '/../config/storyblok-embed.php', 'storyblok-embed');
	}
}
