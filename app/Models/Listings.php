<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listings extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'amount_of_tickets', // New attribute
        'user_id',
        'image',
        'winner_user_id',
    ];

    public function raffleEntries()
    {
        // The related model, the foreign key on the related model, and the local key
        return $this->hasMany(RaffleEntry::class, 'listing_id', 'id');
    }

    public function remainingTickets()
    {
        return $this->amount_of_tickets - $this->raffleEntries()->count();
    }
    
}




