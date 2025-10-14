<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

}
