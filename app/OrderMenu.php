<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMenu extends Model
{
    /**
     * Relate Menu model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu() {
        return $this->belongsTo('App\Menu');
    }

    /**
     * Relate Order model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order() {
        return $this->belongsTo('App\Order');
    }
}
