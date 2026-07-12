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
        Schema::create('patners', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name")->unique(true)->nullable(false);
            $table->string("title")->nullable(false);
            $table->text("overview")->nullable(false);
            $table->text("educationBackground")->nullable(false);
            $table->text("workBackground")->nullable(false);  
            $table->string("phoneNumber")->nullable(false)->unique(true);
            $table->string("secondaryPhoneNumber")->nullable(true);
            $table->jsonb("socialAccounts")->nullable(true);
            $table->string("email")->nullable(false)->unique(true);
            $table->foreignId("admins_id")->constrained()->onDelete("cascade");
            $table->string("slug")->unique(true)->nullable(false);
            $table->boolean("published")->default(false); 
            $table->text("patnersImage")->nullable(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patners');
    }
};
