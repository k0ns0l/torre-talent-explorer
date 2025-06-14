<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('username');
            $table->string('name');
            $table->text('professional_headline')->nullable();
            $table->string('location')->nullable();
            $table->string('picture_url')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'username']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
