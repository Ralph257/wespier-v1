<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentSocialLink extends Model
{
    protected $fillable=[
        'agent_id','social_icon','link','status',
    ];
}
