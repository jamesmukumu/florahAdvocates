<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patners extends Model
{
    /** @use HasFactory<\Database\Factories\PatnersFactory> */
    use HasFactory;
    protected $fillable = ["name","title","overview","educationBackground","workBackground","phoneNumber","secondaryPhoneNumber","socialAccounts","email","admins_id","slug","published","patnersImage"];
}
