<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Relate Menu model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menu() {
        return $this->hasMany('App\Menu');
    }
}
