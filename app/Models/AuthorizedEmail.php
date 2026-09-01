<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizedEmail extends Model
{
    protected $table = 'authorized_emails';
    protected $primaryKey = 'email';
    public $incrementing = false; // Karena primary key-nya bukan integer (string)
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['email'];
}
