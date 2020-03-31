<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Heesapp\Productcart\Exceptions\Name_Att_Missing;
use Heesapp\Productcart\Exceptions\ItemMissing;

/**
 * Description of ProductCartItem
 *
 * @author hassa
 */
class ProductCartItem implements Arrayable {

    /**
     * id of Cart Item
     * @var int
     */
    public $id = null;

    /**
     * Product Model or other 
     * 
     */
    public $model_type;

    /**
     *
     * @var int
     */
    public $model_id;

    /**
     * name of product
     * @var string
     */
    public $name;

    /**
     * price of product
     * @var float
     */
    public $price;

    /**
     * image of product
     * @var string
     */
    public $image = null;

    /**
     * number of product to buy
     * @var float
     */
    public $quantity = 1;

    function __construct($data, $quantity) {
        if (is_array($data)) {
            return $this->CreateFromArray($data);
        }

        return $this->CreateFromModel($data, $quantity);
    }

    /**
     * Create Object Product Item From data Array
     * @param array $data
     * @param float $quantity 
     * @return \Heesapp\Productcart\ProductCartItem Description
     */
    protected function CreateFromArray($data) {
        $this->id = $data['id'];
        $this->model_type = $data['model_type'];
        $this->model_id = $data['model_id'];
        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->image = $data['image'];
        $this->quantity = $data['quantity'];
        return $this;
    }

    /**
     * Create Object From Product Items
     * @param Model $model
     * @param type $quantity
     * @return \Heesapp\Productcart\ProductCartItem
     */
    protected function CreateFromModel(Model $model, $quantity) {
        $this->id  = $model->id;
        $this->model_type = get_class($model);
        $this->model_id = $model->{$model->getKeyName()};
        $this->setName($model);
        $this->setPrice($model);
        $this->setImage($model);
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * use static method to use Create From Model
     *  or Create From Array
     * @param type $data
     * @param type $quantity
     * @return \static
     */
    public static function CreateFrom($data, $quantity = 1) {
        return new static($data, $quantity);
    }

    /**
     * get Product name form method or attribute and set in name
     * @param Model $model
     * @return type
     * @throws Name_Att_Missing
     */
    private function setName(Model $model) {
        if (method_exists($model, 'getName')) {
            $this->name = $model->getName();
            return;
        }
        if ($model->offsetExists('name')) {
            $this->name = $model->name;
            return;
        }
        throw new Name_Att_Missing("name not found in {$this->model_type}");
    }

    /**
     * get name from product object and set this name
     * @param Model $model
     * @return type
     * @throws Name_Att_Missing
     */
    private function setPrice(Model $model) {
        if (method_exists($model, 'getPrice')) {
            $this->price = $model->getPrice();
            return;
        }
        if ($model->offsetExists('price')) {
            $this->price = $model->price;
            return;
        }
        throw new Name_Att_Missing("price Not Found in{$this->model_type}");
    }

    /**
     * get image from  product object and set this image 
     * @param Model $model
     * @return type
     */
    private function setImage(Model $model) {
        if (method_exists($model, 'getImage')) {
            $this->image = $model->getImage();
            return;
        }
        if ($model->offsetExists('image')) {
            $this->image = $model->image;
            return;
        }
    }

    /**
     * Convert Cart Item Object to Array
     * @return array
     */
    public function toArray(): array {
        $CartItem = [
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image,
            'quantity' => $this->quantity,
        ];
        if ($this->id) {
            $CartItem['id'] = $this->id;
        }
        return $CartItem;
    }

}
