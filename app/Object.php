<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Object extends Model
{
     public function categoryId()
    {
        return $this->belongsTo(Mcategory::class);
    }

    public function subcategoryId()
    {
        return $this->belongsTo(Mcategory::class);
    }

     public function cultureId()
    {
        return $this->belongsTo(Culture::class);
    }

    public function monumentId()
    {
        return $this->belongsTo(Monument::class);
    }

    public function typeId()
    {
        return $this->belongsTo(ObjectType::class);
    }
}