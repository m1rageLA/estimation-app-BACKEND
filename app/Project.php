<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'client', 'description', 'preview', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
