<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name', 'price', 'portion', 'category_id',
    ];

    public function category() {
        return $this->hasMany('App\Category');
    }
}
