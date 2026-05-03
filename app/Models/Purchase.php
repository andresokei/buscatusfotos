<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'email', 'media_ids', 'session_id', 'amount', 
        'download_token', 'expires_at', 'payment_status',
        'stripe_session_id', 'download_count', 'last_downloaded_at',
        'last_download_ip',
    ];

    protected $casts = [
        'media_ids' => 'array',
        'expires_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function canDownload(): bool
    {
        $maxDownloads = (int) config('ofertas.descarga.max_intentos', 3);

        return $this->payment_status === 'paid'
            && $this->expires_at
            && $this->expires_at->isFuture()
            && ($maxDownloads <= 0 || $this->download_count < $maxDownloads);
    }
}
