<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'order_id'
    ];
    /**
     * Relate Order model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order() {
        return $this->hasMany('App\Order');
    }

    /**
     * Relate User model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user() {
        return $this->hasMany('App\User');
    }

}
