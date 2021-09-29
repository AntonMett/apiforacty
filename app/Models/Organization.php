<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public function relations()
    {
        return $this->hasMany(Relation::class, 'parent_id', 'id');
    }

}
