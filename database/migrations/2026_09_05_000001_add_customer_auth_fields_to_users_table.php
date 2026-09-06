<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adds the columns needed for customer Google + email/password login.
     * Guarded with hasColumn() checks so it is safe to run even on this
     * database, where the columns were already added by hand.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('phone');
            }
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('profile_photo');
            }
            if (!Schema::hasColumn('users', 'login_method')) {
                $table->enum('login_method', ['google', 'email'])->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('login_method');
            }
        });

        if (!Schema::hasColumn('users', 'password')) {
            return;
        }

        DB::statement('ALTER TABLE users MODIFY password VARCHAR(255) NULL');
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'google_id', 'profile_photo', 'status', 'login_method', 'last_login_at']);
        });
    }
};
