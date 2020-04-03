<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Heesapp\Productcart\Models;

use Illuminate\Database\Eloquent\Model;
use Heesapp\Productcart\Models\WitchlistItem;
use Heesapp\Productcart\Models\ItemCart;
/**
 * Heesapp\Productcart\Models\Witchlist
 *
 * @property int $id
 * @property string $cookie
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Witchlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Witchlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Witchlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Witchlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Witchlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Witchlist whereCookie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Witchlist whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Heesapp\Productcart\Models\Witchlist whereUpdatedAt($value)
 * @mixin \Eloquent
 */

/**
 * Description of Witchlist
 *
 * @author Hassan Elsied 
 */
class Witchlist extends Model {

    /**
     *
     * @var type 
     */
    protected $table = 'witchlists';

    /**
     *
     * @var type 
     */
    public $fillable = ['cookie', 'user_id'];

    /**
     * 
     * @return type
     */
    public function WitchLists() {
        return $this->hasMany(ItemCart::class);
    }

}
