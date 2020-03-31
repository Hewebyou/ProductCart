<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Observers;

use Heesapp\Productcart\Models\Cart;

/**
 * Description of Observer
 *
 * @author hassa
 */
class Observer {

    /**
     *  Listen to the Cart deleting event.
     * @param Cart $cart
     * @return void
     */
    public function deleting(Cart $cart) {
        $cart->CartItems->delete();
    }

}
