<?php

namespace App\Models;

use App\Models\Listings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    public function items()
    {
        return $this->hasMany(Listings::class);
    }

}
