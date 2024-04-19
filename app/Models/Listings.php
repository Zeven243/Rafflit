<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;

class Listings extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'price',
        'amount_of_tickets',
        'user_id',
        'image',
        'winner_user_id',
    ];

    public function raffleEntries()
    {
        return $this->hasMany(RaffleEntry::class, 'listing_id', 'id');
    }

    public function remainingTickets()
    {
        return $this->amount_of_tickets - $this->raffleEntries()->count();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description', 'price', 'amount_of_tickets'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Listing {$eventName}")
            ->dontSubmitEmptyLogs();
    }
}