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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("title")->unique(true)->nullable(false);
            $table->string("slug")->nullable(false);
            $table->mediumText("articleImage")->nullable(false);
            $table->text("articleBody")->nullable(false);
            $table->foreignId("admins_id")->constrained()->onDelete("cascade");
            $table->foreignId("articlesCategories_id")->constrained()->onDelete("cascade");
            $table->text("articleOverview")->nullable(false);
            $table->boolean("published")->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
