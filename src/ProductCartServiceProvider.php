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
        $cartsmigration = __DIR__ . '/../database/migrations/2020_03_29_230006_create_carts_table.php';
        $cartitemsmigration = __DIR__ . '/../database/migrations/2020_03_29_230106_create_items_cart_table.php';
        if ($this->app->runningInConsole()) {
            $this->publishes([$cofigpath => config_path('productcart.php')], 'ProductCartConfig');
            if (!class_exists('CreateCartsTable') || !class_exists('CreateCartItemsTable')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    $cartitemsmigration => database_path('migrations/' . $timestamp . '_create_items_cart_table.php'),
                    $cartsmigration => database_path('migrations/' . $timestamp . '_create_carts_table.php')
                        ], 'ProuductCartmigrations');
            }
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
    }

}
