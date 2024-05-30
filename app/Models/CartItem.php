<?php

namespace App\Models;

use App\Models\Listings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    public function listing()
    {
        return $this->belongsTo(Listings::class);
    }
    
}
