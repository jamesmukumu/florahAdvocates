<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class admins extends Authenticatable implements JWTSubject{
    /** @use HasFactory<\Database\Factories\AdminsFactory> */
    use HasFactory;
     public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = ["name","email","password","phoneNumber","role","password","profilePhoto"];
}
