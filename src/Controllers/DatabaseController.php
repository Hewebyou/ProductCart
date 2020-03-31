<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Controllers;

use Str;
use Auth;
use DB;
use Heesapp\Productcart\Models\Cart;
use Heesapp\Productcart\Models\ItemCart;
use Heesapp\Productcart\Contracts\ProductCartContract;
use Illuminate\Support\Facades\Cookie;
use Heesapp\Productcart\Exceptions\ItemMissing;
use Illuminate\Database\Eloquent\Builder;

/**
 * Description of DatabaseController
 *
 * @author hassa
 */
class DatabaseController implements ProductCartContract {

    /**
     * Cart add to Database Carts Or session Carts
     * @param  array $CartData Cart Data to Store in Database or session
     * 
     */
    public function addCart($CartData) {

        $cart = new Cart;
        $cart->cookie = $this->getCookieElement();
        $cart->user_id = $this->getCartIdentification();
        $cart->subtotal = $CartData['subtotal'];
        $cart->discount = $CartData['discount'];
        $cart->discout_percentage = $CartData['discout_percentage'];
        $cart->coupon_id = $CartData['coupon_id'];
        $cart->shipping_charges = $CartData['shipping_charges'];
        $cart->net_total = $CartData['net_total'];
        $cart->tax = $CartData['tax'];
        $cart->total = $CartData['total'];
        $cart->round_off = $CartData['round_off'];
        $cart->payable = $CartData['payable'];
        $cart->save();
        $items = $CartData['CartItems'];
        unset($CartData['CartItems']);
        foreach ($items as $item) {
            $this->addCartItem($cart->id, $item);
        }
    }

    /**
     * Cart Item add to Database CartItem or session CartItem
     * 
     * @param int $id  of Cart  this item cart to add into cart 
     * @param array $CartItemData CartItem data to store
     * 
     */
    public function addCartItem($id, $CartItemData) {

        $cart = Cart::find($id);
        $CartItem = new ItemCart;
        $CartItem->model_type = $CartItemData->model_type;
        $CartItem->model_id = $CartItemData->model_id;
        $CartItem->name = $CartItemData->name;
        $CartItem->price = $CartItemData->price;
        $CartItem->image = $CartItemData->image;
        $CartItem->quantity = $CartItemData->quantity;
        $CartItem->Cart()->associate($cart);
        $CartItem->save();
    }

    /**
     * Cart  update by Id selected
     * @param int $id  id of Cart 
     * @param array $CartData Cart Data is update
     * 
     */
    public function updateCart($id, $CartData) {
        $cart = Cart::find($id);
        $cart->cookie = $this->getCookieElement();
        $cart->user_id = $this->getCartIdentification();
        $cart->subtotal = $CartData['subtotal'];
        $cart->discount = $CartData['discount'];
        $cart->discout_percentage = $CartData['discout_percentage'];
        $cart->coupon_id = $CartData['coupon_id'];
        $cart->shipping_charges = $CartData['shipping_charges'];
        $cart->net_total = $CartData['net_total'];
        $cart->tax = $CartData['tax'];
        $cart->total = $CartData['total'];
        $cart->round_off = $CartData['round_off'];
        $cart->payable = $CartData['payable'];
        $cart->update();
    }

    /**
     * Cart Item update by id of cartItme
     * @param int $id id of Cart
     * @param array $CartItemData Description
     * 
     */
    public function updateCartItem($id, $CartItemData) {
        $CartItem = ItemCart::find($id);
        $CartItem->model_type = $CartItemData->model_type;
        $CartItem->model_id = $CartItemData->model_id;
        $CartItem->name = $CartItemData->name;
        $CartItem->price = $CartItemData->price;
        $CartItem->image = $CartItemData->image;
        $CartItem->quantity = $CartItem->quantity;
        $CartItem->update();
    }

    /**
     * Remove Cart by cookie Cart
     * 
     * 
     */
    public function removeCart() {
        $cart = Cart::where('cookie', $this->getCart())
                ->where('user_id', $this->getCartIdentification())
                ->first();

        foreach ($cart->CartItems as $item) {
            $this->removeCartItem($item->id);
        }
        $cart->delete();
    }

    /**
     * Remove Cart item by id 
     * @param int $id id of CartItem
     * 
     */
    public function removeCartItem($id) {
        ItemCart::where('id', $id)->delete();
    }

    /**
     * update quantity of Item
     * @param int $id id of Cart Item
     * @param float $quantity value is update
     * 
     */
    public function updateQuantity($id, $quantity) {
        $CartItem = ItemCart::where('id', $id)->first();
        $CartItem->quantity = $quantity;
        $CartItem->update();
    }

    /**
     * get Cart by id 
     * @param int $id id of cart
     * @return \Heesapp\Productcart\Models\Cart 
     */
    public function getCart() {
//    
        $CartDate = Cart::with('CartItems')
                ->where('cookie', $this->getCookieElement())
                ->where('user_id', $this->getCartIdentification())
                ->first();
        
         

        if (!$CartDate && Auth::guard(config('productcart.guard_name'))->check()) {
            $CartDate = Cart::where('cookie', $this->getCookieElement())
                    ->first();
        }
        if ($CartDate) {
            $this->associateUser();
        }
        if (!$CartDate) {
            return [];
        }

        return $CartDate->toArray();
    }

    /**
     * getCart Item by id
     * @param int $id id of Cart
     * @return \Heesapp\Productcart\Models\Cart 
     */
    public function getCartItem($id) {
        return ItemCart::where('id', $id)->first();
    }

    /**
     * get cart identification 
     * @return mixed 
     */
    private function getCartIdentification() {
//assign  forign key cart_user_id to cart
        $user_id = '';
        if (app()->offsetExists('cart_user_id')) {
            $user_id = resolve('cart_user_id');
        }
        if (Auth::guard(config('productcart.gurad_name'))->check()) {
            $user_id = Auth::guard(config('productcart.guard_name'))->id();
        }
        $user_id;
    }

    /**
     * Cookie session of page 
     * @return mixed with key  cookie
     */
    private function getCookieElement() {
        if (!request()->hasCookie(config('productcart.cookie_name'))) {
//create new cookie and assign config file
            $cookie = Str::random(40);
            $parameters = Cookie::make(
                            config('productcart.cookie_name'),
                            $cookie,
                            config('productcart.cookie_lifetime')
            );

            Cookie::queue($parameters);
        } else {

            $cookie = Cookie::get(config('productcart.cookie_name'));
        }

        return $cookie;
    }

    protected function associateUser() {
        $cart = Cart::where('cookie', $this->getCookieElement())->first();
        $cart->user_id = Auth::guard(config('productcart.guard_name'))->id();
        $cart->update();
    }

    /**
     * store data into data base
     * @param array $CartData
     * @param array $newItem
     */
    public function storeCart($CartData, $newItem = null) {

        $cart = Cart::where('cookie', $this->getCookieElement())->first();
        if (!$cart) {
            //Create Cart
            $this->addCart($CartData);
        } else {
            //Update Cart 
            $this->updateCart($cart->id, $CartData);
            if ($newItem) {
                $this->addCartItem($cart->id, $newItem);
            }
        }
    }

    public function updataCartItems($items) {
        foreach ($items as $item) {
            $cartItem = ItemCart::where('id', $item->id)->first();
            $cartItem->name = $item->name;
            $cartItem->price = $item->price;
            $cartItem->image = $item->image;
            $cartItem->update();
        }
    }

}
