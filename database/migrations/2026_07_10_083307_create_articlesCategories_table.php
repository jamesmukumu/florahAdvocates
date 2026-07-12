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
        Schema::create('articlesCategories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("title")->nullable(false);
            $table->text("description")->nullable(false);
            $table->foreignId("admins_id")->constrained()->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles-categories');
    }
};
