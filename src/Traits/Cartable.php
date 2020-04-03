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
     * add model to cart by id
     * @param type $id
     * @param type $quantity
     * @return type
     */
    public static function addToCart($id, $quantity = 1) {
        $class = static::class;
        return app(ProductCart::class)->addCart($class::findOrFail($id), $quantity);
    }

    /*     * remove model  by id
     * @param int $id remove Item with all $quantity
     */

    public static function removeFromCart($id) {
        $class = static::class;
        return app(ProductCart::class)->removeMItem($class::findOrFail($id));
    }

    /**
     * Increment quantity item by model
     * @param Model $model
     * @param type $quntity
     */
    public static function IncrementQuntity($id, $quantity = 1) {
        $class = static::class;
        return app(ProductCart::class)->IncrementItem($class::findOrFail($id), $quantity);
    }

    /**
     * Decrement quantity item by model
     * @param Model $model
     * @param type $quntity
     * @return type
     */
    public static function DecrementQuntity($id, $quantity = 1) {
        $class = static::class;
        return app(ProductCart::class)->DecrementItem($class::findOrFail($id), $quantity);
    }

}
