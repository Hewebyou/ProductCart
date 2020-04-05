<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Traits;
use Heesapp\Productcart\ProductCartItem;
use Heesapp\Productcart\Exceptions\ItemMissing;
/**
 * Description of DataCartabl
 *
 * @author hassa
 */
trait DataCartable {

    /**
     * set subtotal = price * quantity and sum all items subtotal
     */
    private function updataSubtotal() {
        $subtotal = $this->CartItems->sum(function (ProductCartItem $item) {
            return $item->price * $item->quantity;
        });
        $this->subtotal = round($subtotal, 2);
    }

    /**
     * setting a shipping charges to customer the default
     * value is a 0 and order amount = subtotal-discount 
     * for example 100 product each product 3$ 
     * the subtotal = 100*3$ =300$;
     * the discount  = 5% of total subtotal
     * shipping charges = subtotal - subtotal*(5/100) 
     * net total price after discount = 300$  - 300$*(5/100) =285$
     * shipping charges  add to net total price 
     */
    private function updateShippingCharges() {
        $this->shipping_charges = 0;
        $orderAoumnt = $this->subtotal - $this->discount;
        $threshold = config('productcart.shipping_charges_threshold');
        if ($orderAoumnt > 0 && $orderAoumnt < $threshold) {
            $shipping_charges = config('productcart.shipping_charges');

            if ($shipping_charges > 0) {
                $this->shipping_charges = $shipping_charges;
            }
        }
    }

    /**
     * setting round off of total price such as 
     * 99.95$+.05 =100$
     * 99.85$ +.05 = 99.90$ 
     * payable= total + round off ;
     */
    private function updatePayable() {
        $order_off = abs(config('productcart.round_off'));
        if ($order_off <= 1) {
            switch ($order_off) {
                case .05:
                    $this->payable = round($this->total + .05, 1);
                    break;
                case .1 :
                    $this->payable = round($this->total, 1);
                    break;
                case .5 :
                    $this->payable = round($this->total + .5, 1);
                    break;
                case 1:
                    $this->payable = round($this->total);
                    break;
                default :
                    $this->payable = $this->total;
            }
        } else {
            $this->payable = $this->total;
        }
        $this->round_off = round($this->payable - $this->total, 2);
    }

    /**
     * total = net total +tax 
     * example 
     * 100 product each product 3$ 
     * and discount of 40 product = 2% 
     * and tax value = 2% 
     * and shipping charges is a 3$ each 50 product
     * find total value ?
     * solve ==>
     * 1- subtotal = price * quantity = 3$*100 = 300$
     * 2- discount of 100 product = (100/40)*2% = 5%
     * 3- net total price  = 300$ - 300$*(5/100) = 285$
     * 4- total= net total + tax + shipping charges
     *  = 285$ + 300$(2/100) +(100/50)*3$ = 285$+6$+6$ = 297$
     *  
     * 
     * @param  boolean $IsDiscount
     */
    private function updateCartData($IsDiscount) {
        $this->updataSubtotal();
        if (!$IsDiscount) {
            $this->discount = $this->discout_percentage = 0;
            $this->coupon_id = null;
        }
        $this->updateShippingCharges();
   
        $this->net_total = round($this->subtotal - $this->discount + $this->shipping_charges, 2);
        $this->tax = round($this->net_total * config('productcart.tax_precentage') / 100, 2);
        $this->total = round($this->net_total + $this->tax, 2);
        $this->updatePayable();
        
    }

}
