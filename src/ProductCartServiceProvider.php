<?php

namespace Heesapp\Productcart;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Support\ServiceProvider;
use Heesapp\Productcart\Models\Cart;
use Heesapp\Productcart\ProductCart;
use Heesapp\Productcart\ProductWitchList;
use Heesapp\Productcart\Contracts\ProductCartContract;
use Heesapp\Productcart\Observers\Observer;
use Heesapp\Productcart\Console\ConfigCommand;
use Heesapp\Productcart\Console\CartTableCommands;
use Heesapp\Productcart\Console\DriverCommand;
use Heesapp\Productcart\Console\WitchListCommand;

/**
 * Description of ProductCartServiceProvider
 *
 * @author hassa
 */
class ProductCartServiceProvider extends ServiceProvider {

    /**
     * 
     * @return void
     */
    public function boot(): void {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ConfigCommand::class,
                CartTableCommands::class,
                DriverCommand::class,
                WitchListCommand::class,
            ]);
        }
        Cart::observe(Observer::class);
    }

    /**
     * 
     * 
     * @return void
     */
    public function register(): void {
        $cofigpath = __DIR__ . '/../config/ProductCartConfig.php';

        $this->mergeConfigFrom($cofigpath, 'productcart');
        //bind ProductCart Contract

        $this->app->bind(ProductCartContract::class, $this->app['config']['productcart']['driver']);
        //bind ProductCart Class with Contract
        $this->app->bind(ProductCart::class, function($app) {
            return new ProductCart($app->make(ProductCartContract::class));
        });
        
        //bind ProductWitchList Class with Contract
        
        $this->app->bind(ProductWitchList::class , function($app){
            return new ProductWitchList($app->make(ProductCartContract::class));
        });

        $file = 'helpers.php';
        require_once $file;
    }

}
