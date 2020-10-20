<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\View;
use App\Announcement;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

      view()->composer('layouts.app', function($view)
      {
        // get the announcement
        $today = date('Y-m-d');

        $anlist = Announcement::whereDate('start_date', '<=', $today)
          ->whereDate('end_date', '>=', $today)
          ->get();

        $view->with('enonmen3',$anlist);
      });

      // if(env('APP_ENV') === 'production') {
          \URL::forceScheme('https');
      // }

      \Response::macro('attachment', function ($content, $fname) {
          $headers = [
              'Content-type'        => 'text/csv',
              'Content-Disposition' => 'attachment; filename="' . $fname . '"',
          ];
          return \Response::make($content, 200, $headers);
      });
    }
}
