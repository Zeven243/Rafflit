<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'balance'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
