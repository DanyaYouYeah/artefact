<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Model extends Model
{
    public function category()
    {
        return $this->hasOne(App::modelClass('MCategory'), 'id', 'category_id');
    }
}
