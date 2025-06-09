<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'email', 'media_ids', 'session_id', 'amount', 
        'download_token', 'expires_at', 'payment_status'
    ];

    protected $casts = [
        'media_ids' => 'array',
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}