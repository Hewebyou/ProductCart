<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart;

use Illuminate\Contracts\Support\Arrayable;
use Heesapp\Productcart\Contracts\ProductCartContract;
use Heesapp\Productcart\ProductCartItem;
use Heesapp\Productcart\Traits\ProductWitchListable;
use Heesapp\Productcart\Exceptions\ItemMissing;
use Illuminate\Database\Eloquent\Model;

/**
 * Description of ProductWitchList
 *
 * @author Hassan Elsaied
 */
class ProductWitchList implements Arrayable {

    use ProductWitchListable;

    /**
     * id of Cart
     * @var type 
     */
    protected $id = null;

    /**
     *
     * @var array
     */
    protected $WitchListItems = [];

    /**
     *
     * @var \Heesapp\Productcart\Contracts\ProductCartContract;
     */
    protected $ProductCartDriver;

    function __construct(ProductCartContract $ProductCartDriver) {
        $this->ProductCartDriver = $ProductCartDriver;
        $this->WitchListItems = collect($this->WitchListItems);
        if ($WitchListData = $this->ProductCartDriver->getWitchList()) {
            $this->setItems($WitchListData['witch_lists']);
            unset($WitchListData['witch_lists']);
        }
    }

    protected function setItems($Items) {
        foreach ($Items as $Item) {
            $this->WitchListItems->push(ProductCartItem::CreateFrom($Item));
        }
    }

    protected function storeWitchList($IsnewItem = false) {
        if ($IsnewItem) {
            $this->ProductCartDriver->storeWitchList($this->data(), $this->WitchListItems->last());
        } else {
            $this->ProductCartDriver->storeWitchList($this->data());
        }
    }

    protected function updateWitchList($IsnewItem = false) {
        $this->storeWitchList($IsnewItem);
        return $this->data();
    }

    /**
     * 
     * @return array
     */
    public function data() {
        return $this->toArray();
    }

    /**
     * 
     * @return array
     */
    public function toArray(): array {
        $WitchListData['WitchListItems'] = $this->WitchListItems;
        if ($this->id) {
            $WitchListData['id'] = $this->id;
        }
        return $WitchListData;
    }

    /**
     * remove witch list
     */
    public function ClearW() {


        $this->WitchListItems->map(function($row) {
            $this->WitchListItems->forget($row)->values();
        });

        $this->ProductCartDriver->removeWitchList();
    }

    /**
     * check cart item is empty or not
     * @return boolean
     */
    public function isEmptyW() {
        return $this->WitchListItems->isEmpty();
    }

    /**
     * set user
     * @param type $userId
     */
    public function setUserW($userId) {
        app()->singleton('witchlist_user_id', function() use($userId) {
            return $userId;
        });
    }



}
