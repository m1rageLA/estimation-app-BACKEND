<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    protected $fillable = [
        'title', 'description', 'type', 'cost', 'project_id', 'date'
    ];
}
