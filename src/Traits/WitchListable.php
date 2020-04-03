<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Traits;

use Heesapp\Productcart\ProductWitchList;

/**
 * Description of WitchListable
 *
 * @author hassa
 */
trait WitchListable {

    /**
     * 
     * @param type $id
     * 
     * @return type
     */
    public static function addToWithList($id) {
        $class = static::class;
        return app(ProductWitchList::class)->addWhitcList($class::findOrFail($id));
    }

    /**
     * remove item by id
     * 
     * @param type $id
     * @return type
     */
    public static function removeFromWitchList($id) {
        $class = static ::class;
        return app(ProductWitchList::class)->removeMWItem($class::findOrFail($id));
    }

    /**
     * move Item to cart
     * 
     * @param int $id
     * @return type
     */
    public static function moveToCart($id) {
        $class = static::class;
        return app(ProductWitchList::class)->moveMToCart($class::findOrFail($id));
    }

}
