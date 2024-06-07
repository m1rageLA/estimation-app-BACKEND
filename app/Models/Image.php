<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images'; // Имя таблицы, где сохраняются изображения
    protected $fillable = ['name']; // Указываем, что поле name можно заполнять массово
}
