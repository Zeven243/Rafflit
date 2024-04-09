<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;


class Listings extends Model
{
    use HasFactory;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description', 'price'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    
}




