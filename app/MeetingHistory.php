<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingHistory extends Model
{

    protected $fillable=[
        'agent_id','lawyer_id','user_id','meeting_id','meeting_time','duration',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function agent(){
        return $this->belongsTo(Agent::class);
    }
    public function lawyer(){
        return $this->belongsTo(Lawyer::class);
    }

    public function meeting(){
        return $this->belongsTo(ZoomMeeting::class,'meeting_id','meeting_id');
    }

}
