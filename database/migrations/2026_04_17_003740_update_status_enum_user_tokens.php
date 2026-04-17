<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE user_tokens 
            MODIFY status ENUM('AVAILABLE','ACTIVE','USED','EXPIRED') 
            DEFAULT 'AVAILABLE'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE user_tokens 
            MODIFY status ENUM('AVAILABLE','USED','EXPIRED') 
            DEFAULT 'AVAILABLE'
        ");
    }
};