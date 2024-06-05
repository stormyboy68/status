<?php

namespace ASB\Status\Providers;

use ASB\Status\App\AsbClassMap;
use ASB\Status\App\StatusCommand;
use ASB\Status\Models\Status;
use Illuminate\Support\ServiceProvider;


class StatusServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
        $this->app->singleton('AsbStatus',function (){
            return new StatusCommand();
        });
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
    public function register(): void
    {
        foreach (AsbClassMap::getClassMap() as $name => $class) {
            Status::resolveRelationUsing($name, function (Status $model)use($class) {
                return $model->morphedByMany($class, 'statusable', 'statusables')->without(['status']);
            });
        }
    }
}
