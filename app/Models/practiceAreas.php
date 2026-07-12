<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class practiceAreas extends Model
{
    /** @use HasFactory<\Database\Factories\PracticeAreasFactory> */
    use HasFactory;
    protected $table = "practiceAreas";
    protected $fillable = ["overview","slug","title","description","admins_id","published","practiceAreaImage"];
}
