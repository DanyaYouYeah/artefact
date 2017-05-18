<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Category;

class Test extends Model
{
    public function categoryId(){
        return $this->belongsTo(Category::class);
    }
}
