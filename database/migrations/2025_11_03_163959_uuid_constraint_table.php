<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');
            DB::statement('ALTER TABLE users ALTER COLUMN id TYPE uuid USING gen_random_uuid();');
            DB::statement('ALTER TABLE users ALTER COLUMN id SET NOT NULL;');
            DB::statement('ALTER TABLE users ALTER COLUMN id DROP DEFAULT;');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE users ALTER COLUMN id TYPE serial;');
    }
};
