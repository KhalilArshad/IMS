<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mpdf\Mpdf;
class MpdfServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mpdf.wrapper', function ($app) {
            return new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'autoScriptToLang' => true,
                'autoLangToFont' => true
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
