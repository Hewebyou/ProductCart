<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart;

use Str;
use BadMethodCallException;
use Illuminate\Contracts\Support\Arrayable;
use Heesapp\Productcart\Contracts\ProductCartContract;
use Heesapp\Productcart\ProductCartItem;
use Heesapp\Productcart\Traits\ProductCartable;
use Heesapp\Productcart\Traits\DataCartable;
use Heesapp\Productcart\Traits\DiscountCartable;
use Heesapp\Productcart\Exceptions\ItemMissing;



/**
 * Description of ProductCart
 *
 * @author hassa
 */
class ProductCart implements Arrayable {

    use ProductCartable,
        DataCartable,
        DiscountCartable;

    /**
     * id of Cart
     * @var type 
     */
    public $id = null;

    /**
     *
     * @var array
     */
    public $CartItems = [];

    /**
     *
     * @var \Heesapp\Productcart\Contracts\ProductCartContract;
     */
    public $ProductCartDriver;

    /**
     * subtotal = price * quantity
     * @var float
     */
    protected $subtotal = 0;

    /**
     * discount price of product   
     * @var float
     */
    protected $discount = 0;

    /**
     * discount as a 10% or other 
     * @var float 
     */
    protected $discout_percentage = 0;

    /**
     * coupon id
     * @var type 
     */
    protected $coupon_id = null;

    /**
     * $shipping charges to drive house 
     * @var float
     */
    protected $shipping_charges = 0;

    /**
     * net total = subtotal - discount
     * @var float
     */
    protected $net_total = 0;

    /**
     * tax value 
     * @var float
     */
    protected $tax = 0;

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
     * @var float
     */
    protected $total = 0;

    /**
     * if total = 100.35$ the round of value = 100.35$+.05 = 100.40$
     * @var float 
     */
    protected $round_off = 0;

    /**
     * payable 
     * @var float
     */
    protected $payable = 0;

    function __construct(ProductCartContract $ProductCartDriver) {
        $this->ProductCartDriver = $ProductCartDriver;
        $this->CartItems = collect($this->CartItems);
        if ($CartData = $this->ProductCartDriver->getCart()) {
            $this->setCartItems($CartData['CartItems']);
            unset($CartData['CartItems']);
            $this->setProperties($CartData);
        }
        return $CartData;
    }

    /**
     * Create Product Object from data items
     * @param type $Items
     */
    private function setCartItems($Items) {
        foreach ($Items as $Item) {
            $this->CartItems->push(ProductCartItem::CreateFrom($Item));
        }
    }

    public function storeCart($IsnewItem = false) {
        $CartData = $this->toArray();
        if ($IsnewItem) {
           
            $this->ProductCartDriver->storeCart($CartData, $this->CartItems->last());
        } else {
            $this->ProductCartDriver->storeCart($this->data());
        }
    }

    /**
     * 
     * @param boolean $IsnewItem
     * @param boolean $IsDiscount
     * @return array of Cart Object
     */
    public function updateCart($IsnewItem = false, $IsDiscount = false) {
//updata value of Cart Object;
        $this->updateCartData($IsDiscount);
        $this->storeCart($IsnewItem);
        return $this->toArray();
    }

    /**
     * 
     * @return array
     */
    public function data() {
        return $this->toArray($withItems = false);
    }

    /**
     * 
     * @param array Cart attributes
     * @return void Description
     */
    protected function setProperties($attributes) {
        foreach ($attributes as $key => $value) {
            $this->{Str::camel($key)} = $value;
        }
    }

    /**
     * Convert Cart Object to Array
     * @param type $isItems
     * @return array
     */
    public function toArray($isItems = true): array {
        $CartData = [
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'discout_percentage' => $this->discout_percentage,
            'coupon_id' => $this->coupon_id,
            'shipping_charges' => $this->shipping_charges,
            'net_total' => $this->net_total,
            'tax' => $this->tax,
            'total' => $this->total,
            'round_off' => $this->round_off,
            'payable' => $this->payable
        ];
        if ($isItems) {
            $CartData['CartItems'] = $this->CartItems;
        }
        if ($this->id) {
            $CartData['id'] = $this->id;
        }
        return $CartData;
    }

    public function PrintCartData() {
        $decimals = config('productcart.number.decimals');
        $dec_point = config('productcart.number.dec_point');
        $thousands_sep = config('productcart.number.thousands_sep');
        $subtotal = number_format($this->subtotal, $decimals, $dec_point, $thousands_sep);
        $total = ['Subtotal' => $subtotal];
        if ($this->discount > 0) {
            $discount = number_format($this->discount, $decimals, $dec_point, $thousands_sep);
            $total['Discount'] = $discount;
        }
        if ($this->shipping_charges > 0) {
            $shipping_charges = number_format($this->shipping_charges, $decimals, $dec_point, $thousands_sep);
            $total['Shipping Charges'] = $shipping_charges;
        }
        if ($this->subtotal != $this->net_total) {
            $net_total = number_format($this->net_total, $decimals, $dec_point, $thousands_sep);
            $total['net Total'] = $net_total;
        }
        $total['Tax'] = number_format($this->tax, $decimals, $dec_point, $thousands_sep);
        if ($this->round_off !== 0) {
            $total['Total'] = number_format($this->total, $decimals, $dec_point, $thousands_sep);
            $total['Round Off'] = number_format($this->round_off, $decimals, $dec_point, $thousands_sep);
        }
        $total['Payable'] = number_format($this->payable, $decimals, $dec_point, $thousands_sep);
        return $total;
    }

    /**
     * set user
     * @param type $userId
     */
    public function setUser($userId) {
        app()->singleton('cart_user_id', function() use($userId) {
            return $userId;
        });
    }

    /**
     * remove Cart
     */
    public function clearCart() {
        $this->ProductCartDriver->removeCart();
    }

    /**
     * check cart item is empty or not
     * @return boolean
     */
    public function isEmpty() {
        return $this->CartItems->isEmpty();
    }

    /**
     * Serves as a getter for cart properties.
     *
     * @param string Method name
     * @param array Arguments
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $arguments) {
        $property = Str::camel(Str::replaceFirst('get', '', $method));

        if (property_exists($this, $property)) {
            return $this->$property;
        }

        throw new BadMethodCallException('Method [{$method}] does not exist. Check documentation please.');
    }

}
