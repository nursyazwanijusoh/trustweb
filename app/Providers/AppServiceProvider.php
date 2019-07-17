<?php

namespace App\Providers;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;
use Laravel\Passport\Passport;

use Illuminate\Support\ServiceProvider;

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
      Passport::routes();

      /*ADD THIS LINES*/
      $this->commands([
          InstallCommand::class,
          ClientCommand::class,
          KeysCommand::class,
      ]);

      if(config('APP_ENV') === 'production') {
          \URL::forceScheme('https');
      }

      \Response::macro('attachment', function ($content, $fname) {
          $headers = [
              'Content-type'        => 'text/csv',
              'Content-Disposition' => 'attachment; filename="' . $fname . '"',
          ];
          return \Response::make($content, 200, $headers);
      });
    }
}
