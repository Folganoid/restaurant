<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Relate User model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Relate OrderMenu model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderMenu() {
        return $this->hasMany('App\OrderMenu');
    }

    /**
     * Relate Group model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group() {
        return $this->belongsTo('App\Group');
    }
}
