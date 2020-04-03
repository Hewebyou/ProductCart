<?php

use Heesapp\Productcart\ProductCart;
use Heesapp\Productcart\ProductWitchList;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('ProductCart')) {

    /**
     * Returns an 
     * @return Heesapp\Productcart\ProductCart; .   
     */
    function ProductCart(): ProductCart {
        return app(ProductCart::class);
    }

}

if (!function_exists('ProductWitchList')) {

    /**
     * Returns an 
     * @return Heesapp\Productcart\ProductWitchList; .   
     */
    function ProductWitchList(): ProductWitchList {
        return app(ProductWitchList::class);
    }

}