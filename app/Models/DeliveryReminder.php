<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryReminder extends Model
{
    use HasFactory;

    protected $fillable = [ 'listing_id', 'winner_id', 'created_at' ];
}
