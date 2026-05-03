<?php

namespace App\Console\Commands;

use App\Models\Purchase;
use Illuminate\Console\Command;

class CleanExpiredTokens extends Command
{
    protected $signature = 'tokens:clean';
    protected $description = 'Expire old download tokens without deleting purchase history';

    public function handle()
    {
        $expired = Purchase::whereNotNull('download_token')
            ->where('expires_at', '<', now())
            ->update(['download_token' => null]);

        $this->info("Expired {$expired} download tokens");
    }
}
