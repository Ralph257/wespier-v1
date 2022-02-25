<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable=[
        'agent_id','lawyer_id','date','status',
    ];
}
