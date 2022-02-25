<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentZoomCredential extends Model
{
    protected $fillable=[
        'agent_id','zoom_api_key','zoom_api_secret',
    ];
}
