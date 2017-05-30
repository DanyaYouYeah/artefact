<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Mcategory extends Model
{

    protected $fillable = ['slug', 'tittle'];

    public function getModels()
    {
        return $this->hasMany(App::modelClass('Object'))
            ->published()
            ->orderBy('created_at', 'DESC');
    }

    public function parentId()
    {
        return $this->belongsTo(self::class);
    }
}
