<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Contracts;

/**
 * Description of ProductCartContract
 *
 * @author hassa
 */
interface ProductCartContract {

    /**
     * Cart add to Database Carts Or session Carts
     * @param  array $CartData Cart Data to Store in Database or session
     * @return type Description
     */
    public function addCart($CartData);

    /**
     * Cart Item add to Database CartItem or session CartItem
     * 
     * @param int $id  of Cart  or WitchList 
     * @param array $CartItemData CartItem data to store
     * @param boolean $type Description $type determined this id in cart if false if true is a store in WitchList
     * @return type Description
     */
    public function addCartItem($id, $CartItemData, $type);

    /**
     * Cart  update by Id selected
     * @param int $id  id of Cart 
     * @param array $CartData Cart Data is update
     * @return type Description
     */
    public function updateCart($id, $CartData);

    /**
     * Cart Item update by id of cartItme
     * @param int $id id of CartItem
     * @param array $CartItemData Description
     * @return type Description
     */
    public function updateCartItem($id, $CartItemData);

    /**
     * Remove Cart by cookie Cart
     *  @return type Description 
     * 
     */
    public function removeCart();

    /**
     * Remove Cart item by id 
     * @param int $id id of CartItem
     * @param boolean $type remove from cart or witchList
     */
    public function removeCartItem($id, $type);

    /**
     * update quantity of Item
     * @param int $id id of Cart Item
     * @param float $quantity value is update
     * @return type Description
     */
    public function updateQuantity($id, $quantity);

    /**
     * Create Cart Or Update
     * @param array $CartData
     * @param array $IsnewItem 
     * @return type Description
     */
    public function storeCart($CartData, $newItem = null);

    /**
     * get Cart by cookie  
     *
     * @return array 
     */
    public function getCart();

    /**
     * update all items 
     * @param type $items
     * @return type Description
     */
    public function updataCartItems($items);

    /**
     * create the witchList
     * 
     * @param int $WithListData
     * @return array Description
     */
    public function addWitchList($WithListData);

    /**
     * remove witch list session by id 
     * 
     * @param type $id
     * @return type Description
     */
    public function removeWitchList();

    /**
     * get witch List by cookie
     * @return type Description
     */
    public function getWitchList();

    /**
     * store determined the WitchList update or create
     * 
     * @param type $WitchListData
     * @param type $newItem
     * @return type Description
     */
    public function storeWitchList($WitchListData, $newItem = null);

    /**
     * move item from witch list to cart
     * 
     * @param type $id
     * @return type Description
     */
    public function moveToCart($id);

    /**
     * move Item From cart To WitchList 
     * 
     * @param type $id
     * @return type Description
     */
    public function moveToWitchList($id);
}
