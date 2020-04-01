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
use Heesapp\Productcart\Contracts\ProductCartContract;
use Heesapp\Productcart\Observers\Observer;
use Heesapp\Productcart\Console\ConfigCommand;
use Heesapp\Productcart\Console\CartTableCommands;

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
        $cofigpath = __DIR__ . '/../config/ProductCartConfig.php';
        if ($this->app->runningInConsole()) {
            $this->publishes([$cofigpath => config_path('productcart.php')], 'ProductCartConfig');
            $this->commands([ConfigCommand::class, CartTableCommands::class]);
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
        //bind Product Class with Contract
        $this->app->bind(ProductCart::class, function($app) {
            return new ProductCart($app->make(ProductCartContract::class));
        });

        $file = 'helpers.php';
        require_once $file;
    }

}
