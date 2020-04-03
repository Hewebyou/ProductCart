<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Traits;

use Illuminate\Database\Eloquent\Model;
use Heesapp\Productcart\ProductCartItem;
use Heesapp\Productcart\Exceptions\ItemMissing;
use Closure;

/**
 * Description of ProductWitchListable
 *
 * @author hassa
 */
trait ProductWitchListable {

    /**
     * 
     * @param Model $model
     * @return type
     */
    public function addWhitcList(Model $model) {
        if ($this->checkItemExist($model)) {
            return $this->toArray();
        }
        $this->WitchListItems->push(ProductCartItem::CreateFrom($model));
        return $this->updateWitchList($IsnewItem = true);
    }

    /**
     * check model exist or not
     * @param Model $model
     * @return Closure Description
     */
    protected function checkModelExist(Model $model): Closure {
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
    protected function checkItemExist(Model $model) {
        return $this->WitchListItems->contains($this->checkModelExist($model));
    }

    /**
     * 
     * @param type $ItemIndex
     * @throws ItemMissing
     */
    protected function checkItem($ItemIndex) {

        if (!$this->WitchListItems->has($ItemIndex)) {

            throw new ItemMissing("Cart {$ItemIndex} Not found");
        }
    }

    /**
     * remove item from witch list
     * 
     * @param type $ItemIndex
     */
    public function removeWItem($ItemIndex) {
        $this->checkItem($ItemIndex);
        $ItemValue = $this->WitchListItems[$ItemIndex];
        $this->ProductCartDriver->removeCartItem($ItemValue->id, true);
        $ItemIndex = $this->WitchListItems->forget($ItemIndex)->values();
        $modelType = $ItemValue->model_type;
        $modelId = $ItemValue->model_id;
        $model = $modelType::find($modelId);
        return $this->toArray();
    }

    /**
     * remove value by model
     * @param Model $model
     * @return type
     */
    public function removeMWItem(Model $model) {
        if ($this->checkItemExist($model)) {
            $indextId = $this->WitchListItems->search($this->checkModelExist($model));
            $this->removeWItem($indextId);
            return $this->toArray();
        }
        throw new ItemMissing("Product {$model->id} Not found in Witch List");
    }

    /**
     * remove by item
     * @param collection $Item
     */
    public function removeXWItem($Item) {
        if ($Item) {
            foreach ($this->WitchListItems as $key => $value) {
                if ($value->model_id == $Item->model_id) {
                    $this->removeWItem($key);
                    break;
                }
            }
            return $this->toArray();
        }
        throw new ItemMissing("Product {$Item->model_id} Not found in Witch List");
    }

    /**
     * move to cart  by index
     * @param type $Index
     * @return type
     */
    public function moveToCart($Index) {
        $this->checkItem($Index);
        $value = $this->WitchListItems[$Index];
        $this->ProductCartDriver->moveToCart($value->id);
        $Index = $this->WitchListItems->forget($Index)->values();
        return $this->updateWitchList();
    }

    /**
     * move to cart by model
     * @param Model $model
     * @return type
     * @throws ItemMissing
     */
    public function moveMToCart(Model $model) {
        if ($this->checkItemExist($model)) {
            $index = $this->WitchListItems->search($this->checkModelExist($model));
            return $this->moveToCart($index);
        }
        throw new ItemMissing("Product {$model->model_id} Not found in Witch List");
    }

    public function moveXToCart($Item) {
        if ($Item) {
            foreach ($this->WitchListItems as $key => $item) {
                if ($item->model_id == $Item->model_id) {
                    $this->moveToCart($key);
                    break;
                }
            }
            return $this->toArray();
        }
        throw new ItemMissing("Product {$Item->model_id} Not found in Witch List");
    }

}
