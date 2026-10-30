<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sitemap:generate', function () {
    $controller = new \App\Http\Controllers\Public\SitemapController();
    $response = $controller->index();
    file_put_contents(public_path('sitemap.xml'), $response->getContent());
    $this->info('Dynamic sitemap generated successfully at ' . public_path('sitemap.xml'));
})->purpose('Generate dynamic XML sitemap for search engines');

\Illuminate\Support\Facades\Schedule::command('reservations:expire')->everyMinute();
\Illuminate\Support\Facades\Schedule::command('sitemap:generate')->daily();

