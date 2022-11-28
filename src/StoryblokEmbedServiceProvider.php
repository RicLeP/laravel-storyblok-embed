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
    }
}
