<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $connection = 'mysql-auth';

    protected $table = 'users';

}
