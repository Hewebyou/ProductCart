<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Models;

use Illuminate\Database\Eloquent\Model;
use Heesapp\Productcart\Models\ItemCart;
use Heesapp\Productcart\Models\WitchlistItem;
/**
 * Description of CartModel
 *
 * @author hassa
 */

/**
 * Heesapp\Productcart\Models\Cart
 *
 * @property int $id
 * @property string $cookie
 * @property int $user_id
 * @property float $subtotal
 * @property float $discount Description
 * @property float $discout_percentage
 * @property int $coupon_id
 * @property float $shipping_charges
 * @property float $net_total
 * @property float $tax
 * @property float $total
 * @property float $round_off
 * @property float $payable 
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Cart whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Cart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cart extends Model {
    /**
     *
     * @var type 
     */
    protected  $table = 'carts';
    /**
     *
     * @var type 
     */
    protected $fillable = [
        'cookie', 'user_id', 'subtotal', 'discount', 'discout_percentage', 'coupon_id',
        'shipping_charges', 'net_total', 'tax', 'total', 'round_off', 'payable'
    ];

    /**
     * 
     * @return type
     */
    public function CartItems() {
        return $this->hasMany(ItemCart::class);
    }
    
    /**
     * 
     * @return type
     */
    public function WitchLists() {
        return $this->hasMany(WitchlistItem::class);
    }

}
