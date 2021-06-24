<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCredentials extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $table = 'user_credentials';

    public $timestamps = true;
}
