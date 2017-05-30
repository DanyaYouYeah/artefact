<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Culture;

class Monument extends Model
{
    public function cultureId()
    {
        return $this->belongsTo(Culture::class);
    }
}
