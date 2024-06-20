<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'email', 'country', 'avatar', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


