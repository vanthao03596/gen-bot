<?php

namespace App\Providers;

use App\Services\InviteCode;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Container\LaravelContainer;
use BotMan\BotMan\Storages\Drivers\FileStorage;
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
        $this->app->singleton('botman-group', function ($app) {
            $storage = new FileStorage(storage_path('botman'));

            $config = config('botman', []);

            $botGroupConfig = config('bot.token');

            $config['telegram']['token'] = $botGroupConfig;

            $botman = BotManFactory::create($config, new LaravelCache(), $app->make('request'),
                $storage);

            $botman->setContainer(new LaravelContainer($this->app));

            return $botman;
        });

        $this->app->singleton('botman-9', function ($app) {
            $storage = new FileStorage(storage_path('botman'));

            $config = config('botman', []);

            $botGroupConfig = config('bot.token9');

            $config['telegram']['token'] = $botGroupConfig;

            $botman = BotManFactory::create($config, new LaravelCache(), $app->make('request'),
                $storage);

            $botman->setContainer(new LaravelContainer($this->app));

            return $botman;
        });

        $this->app->singleton('dragonsea-bot', function ($app) {
            $storage = new FileStorage(storage_path('botman'));

            $config = config('botman', []);

            $botGroupConfig = config('bot.dragonsea');

            $config['telegram']['token'] = $botGroupConfig;

            $botman = BotManFactory::create($config, new LaravelCache(), $app->make('request'),
                $storage);

            $botman->setContainer(new LaravelContainer($this->app));

            return $botman;
        });

        $this->app->bind('referral_code', InviteCode::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
