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

    public static function RemoveFromWitchList($id) {
        $class = static ::class;
        return app(ProductWitchList::class)->removeMWItem($class::findOrFail($id));
    }

}
