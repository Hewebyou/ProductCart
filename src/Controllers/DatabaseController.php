<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Controllers;

use Str;
use Auth;
use Heesapp\Productcart\Models\Cart;
use Heesapp\Productcart\Models\ItemCart;
use Heesapp\Productcart\Models\Witchlist;
use Heesapp\Productcart\Contracts\ProductCartContract;
use Illuminate\Support\Facades\Cookie;

/**
 * Description of DatabaseController
 *
 * @author hassa
 */
class DatabaseController implements ProductCartContract {
    /**
     * **********************************************************************************
     * Cart  Work With Database  Operation such as create or update Cart
     * and operation with witch list item such as move to witchList and  
     * create or update items
     * ***********************************************************************************
     */

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
            $this->addCartItem($cart->id, $item, false);
        }
    }

    /**
     * Cart Item add to Database CartItem or session CartItem
     * 
     * @param int $id  of Cart  or WitchList 
     * @param array $CartItemData CartItem data to store
     * @param boolean $type Description $type determined this id in cart if false if true is a store in WitchList
     * @return type Description
     */
    public function addCartItem($id, $CartItemData, $type) {
        $CartItem = new ItemCart;
        $CartItem->model_type = $CartItemData->model_type;
        $CartItem->model_id = $CartItemData->model_id;
        $CartItem->name = $CartItemData->name;
        $CartItem->price = $CartItemData->price;
        $CartItem->image = $CartItemData->image;
        $CartItem->quantity = $CartItemData->quantity;
        if (!$type) {
            $cart = Cart::where('id', $id)->first();
            $CartItem->Cart()->associate($cart);
        } else {
            $witchList = Witchlist::where('id', $id)->first();
            $CartItem->WitchList()->associate($witchList);
        }
        $CartItem->save();
    }

    /**
     * Cart  update by Id selected
     * @param int $id  id of Cart 
     * @param array $CartData Cart Data is update
     * 
     */
    public function updateCart($id, $CartData) {
        $cart = Cart::where('id', $id)->first();
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
        $cart = Cart::where('user_id', $this->getCartIdentification())
                ->first();

        if (!$cart && !Auth::guard(config('productcart.guard_name'))->check()) {
            $cart = Cart::where('cookie', $this->getCookieElement())
                    ->first();
        }

        foreach ($cart->CartItems as $item) {
            $this->removeCartItem($item->id);
        }
        Cart::where('id', $cart->id)->delete();
    }

    /**
     * Remove Cart item by id 
     * @param int $id id of CartItem
     * @param boolean $type remove from cart or witchList
     */
    public function removeCartItem($id, $type) {
        if (!$type) {
            $cart = Cart::where('user_id', $this->getCartIdentification())
                    ->first();

            if (!$cart && !Auth::guard(config('productcart.guard_name'))->check()) {
                $cart = Cart::where('cookie', $this->getCookieElement())
                        ->first();
            }
            if ($cart) {
                ItemCart::where('id', $id)->where('cart_id', $cart->id)->delete();
            }
        } else {
            $witchList = Witchlist::where('user_id', $this->getWithListIdentification())
                    ->first();
            if (!$witchList && Auth::guard(config('productcart.guard_name'))->check()) {
                $witchList = Witchlist::where('cookie', $this->getCookieElement())
                        ->first();
            }
            if ($witchList) {
                ItemCart::where('id', $id)->where('witchlist_id', $witchList->id)->delete();
            }
        }
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
                ->where('user_id', $this->getCartIdentification())
                ->first();

        if (!$CartDate && !Auth::guard(config('productcart.guard_name'))->check()) {
            $CartDate = Cart::with('CartItems')
                    ->where('cookie', $this->getCookieElement())
                    ->first();
        }


        if ($CartDate['user_id'] == null && Auth::guard(config('productcart.guard_name'))->check()) {
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

        if (app()->offsetExists('cart_user_id')) {
            return resolve('cart_user_id');
        }
        if (Auth::guard(config('productcart.gurad_name'))->check()) {
            return Auth::guard(config('productcart.guard_name'))->id();
        }
    }

    /**
     * get witchList identification
     * @return type
     */
    private function getWithListIdentification() {

        if (app()->offsetExists('witchlist_user_id')) {

            return resolve('witchlist_user_id');
        }

        if (Auth::guard(config('productcart.gurad_name'))->check()) {

            return Auth::guard(config('productcart.guard_name'))->id();
        }
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
        $cart = Cart::where('cookie', $this->getCookieElement())->update([
            'user_id' => Auth::guard(config('productcart.guard_name'))->id()
        ]);
    }

    protected function associateWUser() {
        $witchList = Witchlist::where('cookie', $this->getCookieElement())->update([
            'user_id' => Auth::guard(config('productcart.guard_name'))->id()
        ]);
    }

    /**
     * store data into data base
     * @param array $CartData
     * @param array $newItem
     */
    public function storeCart($CartData, $newItem = null) {

        $cart = Cart::with('CartItems')
                ->where('user_id', $this->getCartIdentification())
                ->first();

        if (!$cart && !Auth::guard(config('productcart.guard_name'))->check()) {
            $cart = Cart::with('CartItems')
                    ->where('cookie', $this->getCookieElement())
                    ->first();
        }
        if (!$cart) {
            //Create Cart
            $this->addCart($CartData);
        } else {
            //Update Cart 
            $this->updateCart($cart->id, $CartData);
            if ($newItem) {
                $this->addCartItem($cart->id, $newItem, false);
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

    /**
     * **********************************************************************************
     * Witch List Work With Database  Operation such as create or update WitchList
     * and operation with witch list item such as move to cart and  
     * create or update
     * ***********************************************************************************
     */

    /**
     * create the witchList
     * 
     * @param array $WitchhListData
     * @return array Description
     */
    public function addWitchList($WitchhListData) {
        $witchlist = new Witchlist;
        $witchlist->cookie = $this->getCookieElement();
        $witchlist->user_id = $this->getWithListIdentification();
        $witchlist->save();
        $WitchListItems = $WitchhListData['WitchListItems'];
        foreach ($WitchListItems as $Item) {
            $this->addCartItem($witchlist->id, $Item, true);
        }
    }

    /**
     * remove witch list session by id 
     *  
     * @return boolean Description
     */
    public function removeWitchList() {
        $witchList = Witchlist::where('user_id', $this->getWithListIdentification())
                ->first();
        if (!$witchList && !Auth::guard(config('productcart.guard_name'))->check()) {
            $witchList = Witchlist::where('cookie', $this->getCookieElement())
                    ->first();
        }
        if ($witchList) {
            foreach ($witchList->WitchLists as $Item) {
                $this->removeCartItem($Item->id, true);
            }
            return Witchlist::where('id', $witchList->id)->delete();
        }
    }

    /**
     * store determined the WitchList update or create
     * 
     * @param type $WitchListData
     * @param type $newItem
     * @return type Description
     */
    public function storeWitchList($WitchListData, $newItem = null) {
        $witchList = Witchlist::
                where('user_id', $this->getWithListIdentification())
                ->first();
        if (!$witchList && !Auth::guard(config('productcart.guard_name'))->check()) {
            $witchList = Witchlist::where('cookie', $this->getCookieElement())
                    ->first();
        }
        if (!$witchList) {
            $this->addWitchList($WitchListData);
        } else {
            if ($newItem) {
                $this->addCartItem($witchList->id, $newItem, true);
            }
        }
    }

    /**
     * get witch List by cookie
     * @return type Description
     */
    public function getWitchList() {
        $WitchListData = Witchlist::with('WitchLists')
                ->where('user_id', $this->getWithListIdentification())
                ->first();

        if (!$WitchListData && !Auth::guard(config('productcart.guard_name'))->check()) {
            $WitchListData = Witchlist::with('WitchLists')
                    ->where('cookie', $this->getCookieElement())
                    ->first();
        }

        if ($WitchListData['user_id'] == null && Auth::guard(config('productcart.guard_name'))->check()) {
            $this->associateWUser();
        }

        if (!$WitchListData) {
            return [];
        }
        return $WitchListData->toArray();
    }

    /**
     * dissociate in with list and associate in cart 
     * 
     * @param type $id
     * @return type Description
     */
    public function moveToCart($id) {
        $Cart = Cart:: where('user_id', $this->getCartIdentification())
                ->first();
        if (!$Cart && !Auth::guard(config('productcart.guard_name'))->check()) {
            $Cart = Cart:: where('cookie', $this->getCookieElement())
                    ->first();
        }

        if ($Cart) {

            $Item = ItemCart::where('id', $id)->first();
            if ($Item && $Item->witchlist_id != null) {
                $Item->Cart()->associate($Cart);
                $Item->WitchList()->dissociate();
                $Item->update();
            }
        }
    }

    /**
     * dissociate in Cart and associate in witchList 
     * 
     * @param type $id
     * @return type Description
     */
    public function moveToWitchList($id) {
        $WitchList = Witchlist::
                where('user_id', $this->getWithListIdentification())
                ->first();
        if (!$WitchList && !Auth::guard(config('productcart.guard_name'))->check()) {
            $WitchList = Witchlist::where('cookie', $this->getCookieElement())
                    ->first();
        }
        if ($WitchList) {
            $Item = ItemCart::where('id', $id)->first();
            if ($Item && $Item->cart_id != null) {
                $Item->Cart()->dissociate();
                $Item->WitchList()->associate($WitchList);
                $Item->update();
            }
        }
    }

}
