<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Heesapp\Productcart\Facades;
use \Illuminate\Support\Facades\Facade;
/**
 * Description of Carts
 *
 * @author hassa
 */
class Carts extends Facade{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'cart';
    }

}
