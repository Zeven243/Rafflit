<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'balance'
    ];


    /**
     * Define the inverse one-to-one relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}