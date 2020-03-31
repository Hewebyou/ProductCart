<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Traits;

use Heesapp\Productcart\ProductCart;

/**
 * Description of Cartable
 *
 * @author hassa
 */
trait Cartable {
    /**
     * 
     * @param type $id
     * @param type $quantity
     * @return type
     */
    public static function addToCart($id, $quantity = 1) {
        $class = static::class;
        return app(ProductCart::class)->addCart($class::findOrFail($id), $quantity);
    }

}
