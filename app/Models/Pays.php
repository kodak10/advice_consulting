<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
