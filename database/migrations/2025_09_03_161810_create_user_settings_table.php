<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('theme')->default('light'); // light, dark, system
            $table->string('default_view')->default('grid'); // grid, list
            $table->integer('links_per_page')->default(20);
            $table->boolean('show_descriptions')->default(true);
            $table->boolean('show_favicons')->default(true);
            $table->boolean('auto_generate_thumbnails')->default(true);
            $table->string('default_link_privacy')->default('private'); // private, public
            $table->json('dashboard_widgets')->nullable(); // array of enabled widgets
            $table->json('notification_preferences')->nullable(); // notification settings
            $table->string('avatar')->nullable(); // profile image path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
