<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enquiries extends Model
{
    /** @use HasFactory<\Database\Factories\EnquiriesFactory> */
    use HasFactory;
    protected $fillable = ["email","firstName","lastName","message","cf-turnstile-response","status","phoneNumber"];
}
