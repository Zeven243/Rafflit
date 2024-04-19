<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;

class RaffleEntry extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'listing_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Raffle entry {$eventName}")
            ->dontSubmitEmptyLogs();
    }
}