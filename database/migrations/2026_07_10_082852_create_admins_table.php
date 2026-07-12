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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name")->unique(true)->nullable(false);
            $table->string("email")->unique(true)->nullable(false);
            $table->enum("role",["Advocate","Other"])->default("Advocate")->nullable(true);
            $table->string("phoneNumber")->nullable(false)->unique(true);
            $table->string("password")->nullable(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
