<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articles extends Model
{
    /** @use HasFactory<\Database\Factories\ArticlesFactory> */
    use HasFactory;
    protected $fillable = ["title","slug","articleImage","articleBody","admins_id","articlesCategories_id","articleOverview","published"];
}
