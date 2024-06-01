<?php

namespace App\Models;

use DB;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listings extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'user_id',
        'full_price',
        'amount_of_tickets',
        'image_path',
        'tickets_sold',
        'winner_user_id',
        'company',
        'potential_tickets', 

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variations()
    {
        return $this->hasMany(Variation::class, 'listing_id');
    }

    // Listing.php

    public function existsInCart()
    {
        return DB::table('cart_items')
            ->where('listing_id', $this->id)
            ->exists();
    }



}