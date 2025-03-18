<?php

namespace Sntaks\Daraja;

use Illuminate\Support\ServiceProvider;

class MpesaServiceProvider extends ServiceProvider {
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/../config/mpesa.php', 'mpesa');
    }

    public function boot(): void {
        // Publish config file to Laravel's config folder
        $this->publishes([
            __DIR__ . '/../config/mpesa.php' => config_path('mpesa.php'),
        ], 'config');
    }
}