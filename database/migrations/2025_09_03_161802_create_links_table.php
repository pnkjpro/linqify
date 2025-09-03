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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('link_type_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('url');
            $table->text('description')->nullable();
            $table->json('tags')->nullable(); // array of tags
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_public')->default(false);
            $table->integer('click_count')->default(0);
            $table->string('meta_title')->nullable(); // scraped from URL
            $table->text('meta_description')->nullable(); // scraped from URL
            $table->string('meta_image')->nullable(); // scraped from URL
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'category_id']);
            $table->index(['user_id', 'is_favorite']);
            $table->index(['user_id', 'is_public']);
            $table->index(['user_id', 'created_at']);
            $table->index('click_count');
            $table->fullText(['title', 'description', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
