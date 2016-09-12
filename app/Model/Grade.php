<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $connection = 'mysql-auth';    

    protected $table = 'grade';

    protected $primaryKey = 'id';
        
    public function users()
    {
        return $this->belongsTo('App\Model\User_Wechat', 'userId', 'id');
    }
}
