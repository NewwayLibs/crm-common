<?php

namespace Newway\CrmCommon;

use Illuminate\Support\ServiceProvider;

class CrmCommonServiceProvider extends ServiceProvider {

   public function boot() {
	    $this->publishes([
			__DIR__.'/../config/service-info.php' => config_path('service-info.php'),
		], 'crm-common');

        $this->loadRoutesFrom(__DIR__.'/routes.php');

    }

    public function register() {
        $this->mergeConfigFrom( __DIR__.'/../config/service-info.php', 'service-info');
    }

    public function provides() {
        return ['crm-common'];
    }
}
