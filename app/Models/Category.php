<?php

// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name'];

    public function listings()
    {
        return $this->hasMany(Listings::class);
    }
}

