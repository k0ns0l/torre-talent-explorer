<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            // Make username nullable and add opportunity fields
            $table->string('username')->nullable()->change();
            $table->string('opportunity_id')->nullable();
            $table->string('type')->default('profile'); // 'profile' or 'opportunity'
            $table->text('summary')->nullable();
            $table->string('company_name')->nullable();
            $table->decimal('min_salary', 10, 2)->nullable();
            $table->decimal('max_salary', 10, 2)->nullable();
            
            // Update unique constraint
            $table->dropUnique(['user_id', 'username']);
        });
        
        // Add new composite unique constraint
        Schema::table('favorites', function (Blueprint $table) {
            $table->unique(['user_id', 'username', 'opportunity_id'], 'favorites_user_item_unique');
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique('favorites_user_item_unique');
            $table->string('username')->nullable(false)->change();
            $table->dropColumn(['opportunity_id', 'type', 'summary', 'company_name', 'min_salary', 'max_salary']);
            $table->unique(['user_id', 'username']);
        });
    }
};
