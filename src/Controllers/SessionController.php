<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Heesapp\Productcart\Controllers;
use Heesapp\Productcart\Contracts\ProductCartContract;
/**
 * Description of SessionController
 *
 * @author hassa
 */
class SessionController implements ProductCartContract{
 
  /**
     * Cart add to Database Carts Or session Carts
     * @param  array $CartData Cart Data to Store in Database or session
     * 
     */
    public function addCart($CartData){
        
    }

    /**
     * Cart Item add to Database CartItem or session CartItem
     * 
     * @param int $id  of Cart  this item cart to add into cart 
     * @param array $CartItemData CartItem data to store
     * 
     */
    public function addCartItem($id, $CartItemData){
        
    }

    /**
     * Cart  update by Id selected
     * @param int $id  id of Cart 
     * @param array $CartData Cart Data is update
     * 
     */
    public function updateCart($id, $CartData){
        
    }

    /**
     * Cart Item update by id of cartItme
     * @param int $id id of CartItem
     * @param array $CartItemData Description
     * 
     */
    public function updateCartItem($id, $CartItemData){
        
    }

    /**
     * Remove Cart by cookie Cart
     *   
     * 
     */
    public function removeCart(){
        
    }

    /**
     * Remove Cart item by id 
     * @param int $id id of CartItem
     * 
     */
    public function removeCartItem($id){
        
    }

    /**
     * update quantity of Item
     * @param int $id id of Cart Item
     * @param float $quantity value is update
     * 
     */
    public function updateQuantity($id, $quantity){
        
    }

    /**
     * Create Cart Or Update
     * @param array $CartData
     * @param array $IsnewItem 
     */
    public function storeCart($CartData,$newItem = null){
        
    }

    /**
     * get Cart by cookie  
     *
     * @return array 
     */
    public function getCart(){
        
    }
    
    /**
     * update all items 
     * @param type $items
     */
    public function updataCartItems($items){
        
    }
}
