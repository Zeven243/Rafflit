<?php

namespace App\Models;

use DB;
use App\Models\User;
use App\Models\Category;
use App\Models\DeliveryReminder;
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
        'cover_image_path',
        'image_paths',
        'tickets_sold',
        'winner_user_id',
        'company',
        'potential_tickets',
        'is_active',
        'SKU',
        'delivery_confirmed',
        'item_condition',
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

    public function seller()
    {
        return $this->belongsTo(User::class, 'company', 'company');
    }

    public function existsInCart()
    {
        return DB::table('cart_items')
            ->where('listing_id', $this->id)
            ->exists();
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    public function deliveryReminders()
    {
        return $this->hasMany(DeliveryReminder::class);
    }
}
