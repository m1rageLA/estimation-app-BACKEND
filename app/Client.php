<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    // Указываем поля, которые могут быть массово назначены
    protected $fillable = ['name', 'email'];
}


