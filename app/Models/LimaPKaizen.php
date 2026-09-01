<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LimaPKaizen extends Model
{
    protected $guarded = ['id'];

    public function content()
    {
        return $this->belongsTo(LimaPContent::class, 'lima_p_content_id');
    }
}
