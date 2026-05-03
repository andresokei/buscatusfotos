<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE purchases MODIFY download_token VARCHAR(255) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE purchases MODIFY download_token CHAR(36) NULL');
    }
};
