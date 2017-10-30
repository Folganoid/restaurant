<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name', 'price', 'portion', 'category_id',
    ];

    /**
     * Relate Category model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo('App\Category');
    }

    /**
     * Relate Order model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderMenu() {
        return $this->hasMany('App\OrderMenu');
    }
}
