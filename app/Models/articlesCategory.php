<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class articlesCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ArticlesCategoryFactory> */
    use HasFactory;
    protected $table = "articlesCategories";
    protected $fillable = ["title","description","admins_id"];
}
