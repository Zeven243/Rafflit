<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaffleEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listings::class, 'listing_id');

    }
}