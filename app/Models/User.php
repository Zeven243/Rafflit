<?php

namespace App\Models;

use App\Models\Search;
use App\Models\Listings;
use App\Models\RaffleEntry;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\ResetPasswordNotification;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_picture',
        'user_type',
        'company',
        'vat_number',
        'selling_preference',
        'shipping_address',
        'terms_accepted',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'terms_accepted' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['first_name', 'last_name', 'email'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "User {$eventName}")
            ->dontSubmitEmptyLogs();
    }

    // Define the one-to-many relationship with Listings
    public function listings()
    {
        return $this->hasMany(Listings::class);
    }

    // Define the one-to-many relationship with RaffleEntries
    public function raffleEntries()
    {
        return $this->hasMany(RaffleEntry::class);
    }

    public function searches()
    {
        return $this->hasMany(Search::class);
    }

    // Accessor for the company attribute
    public function getCompanyAttribute($value)
    {
        if ($this->user_type === 'individual') {
            return $this->first_name . ' ' . $this->last_name;
        }

        return $value;
    }

    // Mutator for the vat_number attribute
    public function setVatNumberAttribute($value)
    {
        if ($this->user_type === 'business') {
            $this->attributes['vat_number'] = $value;
        } else {
            $this->attributes['vat_number'] = null;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    protected function resetUrl($token)
    {
        return url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));
    }
}
