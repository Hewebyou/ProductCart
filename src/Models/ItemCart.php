<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Models;

use Illuminate\Database\Eloquent\Model;
use Heesapp\Productcart\Models\Cart;
use Heesapp\Productcart\Models\Witchlist;

/**
 * Description of CartItemModel
 *
 * @author hassa
 */

/**
 * Heesapp\Productcart\Models\Cart
 *
 * @property int $id
 * @property int $cart_id
 * @property int $witchlist_id
 * @property string $model_type
 * @property int $model_id
 * @property string $name
 * @property float $price
 * @property string $image
 * @property float $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\ItemCart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\ItemCart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\ItemCart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\ItemCart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\ItemCart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\ItemCart whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\ItemCart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\ItemCart whereUpdatedAt($value)
 * @mixin \Eloquent
 * 
 */
class ItemCart extends Model {

    /**
     *
     * @var type 
     */
    protected $table = 'items_cart';

    /**
     *
     * @var type 
     */
    protected $fillable = ['cart_id', 'witchlist_id', 'model_type', 'model_id', 'name', 'price', 'image', 'quantity'];

    /**
     * 
     * @return type
     */
    public function Cart() {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function WitchList() {
        return $this->belongsTo(Witchlist::class, 'witchlist_id');
    }

}
