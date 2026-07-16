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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("firstName")->nullable(false);
            $table->string("lastName")->nullable(false);
            $table->string("email")->nullable(false);
            $table->string('phoneNumber')->nullable(false);
            $table->text('message')->nullable(false);
            $table->mediumText('cf-turnstile-response')->nullable(false)->unique(true);
            $table->enum("status",['pending','closed','disputed'])->nullable(false)->default('pending');
    

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
