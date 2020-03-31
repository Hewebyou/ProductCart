<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Traits;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Heesapp\Productcart\ProductCartItem;
use Heesapp\Productcart\Exceptions\ItemMissing;

trait ProductCartable {

    /**
     * 
     * @param Model $model
     * @param type $quantity
     * @return array of Object Cart
     */
    public function addCart(Model $model, $quantity = 1) {
        if ($this->checkItemExist($model)) {
            //get Item
            $ItemIndex = $this->CartItems->search($this->checkModelExist($model));
            // increment quantity
            return $this->IncrementQuntity($ItemIndex, $quantity);
        }

        $this->CartItems->push(ProductCartItem::CreateFrom($model, $quantity));

        return $this->updateCart($IsnewItem = true);
    }

    /**
     * check model exist or not
     * @param Model $model
     * @return Closure Description
     */
    private function checkModelExist(Model $model): Closure {
        return function (ProductCartItem $item) use ($model) {
            return $item->model_type == get_class($model) &&
                    $item->model_id == $model->{$model->getKeyName()};
        };
    }

    /**
     * check object is this add found or not 
     * @param Model $model
     * @return type
     */
    private function checkItemExist(Model $model) {
        return $this->CartItems->contains($this->checkModelExist($model));
    }

    /**
     * Increment Cart Item
     * @param model $Item
     * @param int $quntity
     * @return array of Cart Object
     */
    public function IncrementQuntity($ItemIndex, $quntity = 1) {
        //check item in CartItems array;
        $this->checkItem($ItemIndex);
        $this->CartItems[$ItemIndex]->quantity += $quntity;

        //set value in database
        
        $this->ProductCartDriver->updateQuantity(
                $this->CartItems[$ItemIndex]->id,
                $this->CartItems[$ItemIndex]->quantity);
        return $this->updateCart();
    }

    public function DecrementQuntity($ItemIndex, $quntity = 1) {

        $this->checkItem($ItemIndex);
        if ($this->CartItems[$ItemIndex]->quantity < $quntity) {
            $this->removeCartItem($ItemIndex);
        }
        $this->CartItems[$ItemIndex]->quantity -= $quntity;

        $id = $this->CartItems[$ItemIndex]->id;

        $quty = $this->CartItems[$ItemIndex]->quantity;
        $this->ProductCartDriver->updateQuantity($id, $quty);
        return $this->updateCart();
    }

    private function checkItem($ItemIndex) {

        if (!$this->CartItems->has($ItemIndex)) {

            throw new ItemMissing("Cart {$ItemIndex} Not found");
        }
    }

    /**
     * 
     * @param array $Item
     * @return array of Cart Object
     */
    public function removeCartItem($Item) {
        $this->checkItem($Item);
        $Itemvalue = $this->CartItems[$Item];
        $this->ProductCartDriver->removeCartItem($Itemvalue->id);
        $Item = $this->CartItems->forget($Item)->values();
        $modelType = $Itemvalue->model_type;
        $modelId = $Itemvalue->model_id;
        $model = $modelType::find($modelId);
        return $this->updateCart();
    }

    /**
     * refresh cart items of price , name , image at changed by user
     * @return array
     */
    public function RefreshCartItems() {
        $IsDiscount = true;

        $this->CartItems->transform(function(ProductCartItem $item) use (&$IsDiscount) {
            $model = $item->model_type::find($item->model_id);
            $cartItem = ProductCartItem::CreateFrom($model);
            if ($cartItem->price != $item->price) {
                $IsDiscount = false;
            }
            $item->name = $cartItem->name;
            $item->price = $cartItem->price;
            $item->image = $cartItem->image;
            return $item;
        });
        $this->ProductCartDriver->updataCartItems($this->CartItems);
        $this->updateCartData($IsDiscount);
        $this->ProductCartDriver->updateCart($this->id, $this->data());
        return $this->toArray();
    }

}
