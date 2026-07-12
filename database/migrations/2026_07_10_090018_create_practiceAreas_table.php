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
        Schema::create('practiceAreas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("title")->nullable(false)->unique(true);
            $table->string("slug")->nullable(false)->unique(true);
            $table->text("practiceAreaImage")->nullable(false);
            $table->text("overview")->nullable(false);
            $table->text("description")->nullable(false);
            $table->foreignId("admins_id")->constrained()->onDelete("cascade");
            $table->boolean("published")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice-areas');
    }
};
