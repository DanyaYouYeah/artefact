<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Monument;
use App\Mcategory;

class Culture extends Model
{
    public function categoryId()
    {
        return $this->belongsTo(Mcategory::class);
    }

    public function monumentId()
    {
        return $this->belongsTo(Monument::class);
    }

    public function getModels()
    {
        return $this->hasMany(App::modelClass('Object'))
            ->published()
            ->orderBy('created_at', 'DESC');
    }
}
