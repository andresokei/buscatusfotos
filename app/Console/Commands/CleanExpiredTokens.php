<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Purchase;

class CleanExpiredTokens extends Command
{
    protected $signature = 'tokens:clean';
    protected $description = 'Clean expired download tokens';

    public function handle()
    {
        $expired = Purchase::where('expires_at', '<', now())->count();
        
        Purchase::where('expires_at', '<', now())->delete();
        
        $this->info("Eliminados {$expired} tokens expirados");
    }
}