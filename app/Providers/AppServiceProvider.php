<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFour();

        // Fix "Malformed UTF-8 characters" error in Livewire AJAX responses.
        // Clean rendered component HTML before it reaches json_encode in JsonResponse.
        //
    }
}
