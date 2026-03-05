<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationSetting extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'days_before'
    ];

    protected $casts = [
        'days_before' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
