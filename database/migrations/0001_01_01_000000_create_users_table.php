<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', [
                'donor',
                'admin',
                'super_admin',
                'President',
                'VicePresident',
                'secretary',
                'assistant_secretary',
                'treasurer',
                'assistant_treasurer',
                'committee_member'
            ])->default('donor');

            $table->enum('donor_type', ['monthly', 'one_time'])->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
