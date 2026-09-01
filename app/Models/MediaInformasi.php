<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaInformasi extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'media_informasi';

    protected $guarded = ['id'];
}
