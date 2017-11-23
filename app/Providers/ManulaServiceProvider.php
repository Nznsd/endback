<?php

namespace NTI\Providers;

use NTI\Repository\Libraries\ManulaIntegrate;
use Illuminate\Support\ServiceProvider;

class ManulaServiceProvider extends ServiceProvider
{

    protected $currentOrigin = "";

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(ManulaIntegrate $integrate)
    {
            $pathname1 = "registration-guide";

            $pathname2 = "application-guide";

            $this->currentOrigin = env('APP_URL');

            if(str_contains($this->app->request->getRequestUri(), $pathname1)){


                  $integrate->setIntegrateUrl("http://www.manula.com/integrate");
        
                  $integrate->setAccount("omniswift");
        
                  $integrate->setGroup("");
        
                  $integrate->setManual("mynti-v2-students-registration-guide");
        
                  $integrate->setManualUrl( $this->currentOrigin . "/{$pathname1}");
        
                  $integrate->execute();
            }else if(str_contains($this->app->request->getRequestUri(), $pathname2)){

                    $integrate->setIntegrateUrl("http://www.manula.com/integrate");
        
                    $integrate->setAccount("omniswift");
        
                    $integrate->setGroup("");
        
                    $integrate->setManual("mynti-v2-application-guide");
        
                    $integrate->setManualUrl( $this->currentOrigin . "/{$pathname2}");
        
                    $integrate->execute();
            }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ManulaIntegrate', function($app){
            return new ManulaIntegrate();
        });
    }
}
