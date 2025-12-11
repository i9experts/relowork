<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        try {
            $defaultMailer = config('mail.default');
            if ($defaultMailer === 'smtp') {
                $smtp = config('mail.mailers.smtp');
                $missing = [];
                foreach (['host','port','username','password'] as $k) {
                    if (empty($smtp[$k])) { $missing[] = $k; }
                }
                $from = (string) (config('mail.from.address') ?? '');
                if ($from === '') { $missing[] = 'from.address'; }
                if (!empty($missing)) {
                    Log::warning('smtp configuration incomplete', ['missing' => $missing]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('mail diagnostics failed', ['error' => $e->getMessage()]);
        }
    }
}
