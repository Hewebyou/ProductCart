<?php

use Heesapp\Productcart\ProductCart;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('ProductCart')) {

    /**
     * Returns an 
     * @return Heesweb\Laravelcarts\main\MainCart .   
     */
    function ProductCart() : MainCart {
        return app(MainCart::class);
    }

}