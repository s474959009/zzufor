<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $softDelete = true;

    protected $fillable = [
        'title',
        'userId',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo('App\Model\User_Lost', 'userId', 'id');
    }

}
