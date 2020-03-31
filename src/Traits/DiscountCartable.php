<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Traits;

use Heesapp\Productcart\Exceptions\DiscountError;

/**
 * Description of DiscountCartable
 *
 * @author hassa
 */
trait DiscountCartable {

    /**
     * discount = subtotal * discount percentage /100
     * @param type $percentage
     * @param type $coupon_id
     * @return type
     * @throws DiscountError
     */
    public function ApplyDiscount($percentage, $coupon_id = null) {
        if ($percentage > 100) {
            throw new DiscountError("maximum of Prescentage value is 100%");
        }
        if ($this->subtotal = 0) {
            throw new DiscountError("Discount cannot be applied on Product added.");
        }
        $this->discout_percentage = $percentage;
        $this->discount = round(($this->subtotal * $percentage) / 100, 2);
        $this->coupon_id = $coupon_id;
        return $this->updateCart($IsnewItem = false, $IsDiscount = true);
    }

    /**
     * 
     * @param type $amount
     * @param type $coupon_id
     * @return type
     * @throws DiscountError
     */
    public function ApplyDiscountValue($amount, $coupon_id = null) {
        if ($amount > $this->subtotal) {
            throw new DiscountError("The discount amount cannot be more that subtotal of the cart");
        }
        $this->discount = round($amount, 2);
        $this->discout_percentage = 0;
        $this->coupon_id = $coupon_id;
        return $this->updateCart($IsnewItem = false, $IsDiscount = true);
    }

}
