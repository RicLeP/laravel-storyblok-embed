<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable caching
    |--------------------------------------------------------------------------
    |
    | Enable caching the Storyblok API response.
    |
    */
    'cache' => env('STORYBLOK_CACHE', true),

    /*
    |--------------------------------------------------------------------------
    | Cache duration
    |--------------------------------------------------------------------------
    |
    | Specifies how many minutes to cache responses from Storkyblok for.
    |
    */
    'cache_duration' => env('STORYBLOK_DURATION','60'),
];
