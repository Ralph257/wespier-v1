<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Appointment;
use App\Message;
use App\User;
use App\BannerImage;
use App\ManageText;
use Auth;
use Pusher\Pusher;
class AgentMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent');
    }
    public function index(){
        $agent=Auth::guard('agent')->user();
        $users=Appointment::with('user')->where('agent_id',$agent->id)->groupBy('user_id')->select('user_id')->get();
        $profile_image=BannerImage::first();
        $profile_image=$profile_image->default_profile;
        $website_lang=ManageText::all();
        return view('agent.message.index',compact('users','profile_image','website_lang'));
    }

    public function messageBox($id){
        $user=User::find($id);
        $user_id=$user->id;
        $agent=Auth::guard('agent')->user();
        $my_id=$agent->id;
        Message::where(['agent_id'=>$my_id,'user_id'=>$user_id])->update(['agent_view'=>1]);

        $messages = Message::where(['agent_id'=>$my_id,'user_id'=>$user_id])->get();


        $users=Appointment::with('user')->where('agent_id',$agent->id)->groupBy('user_id')->select('user_id')->get();
        $profile_image=BannerImage::first();
        $profile_image=$profile_image->default_profile;

        $website_lang=ManageText::all();
        return view('agent.message.single-message',compact('users','profile_image','messages','user_id','website_lang'));


    }

    public function getMessage($user_id){

        $agent=Auth::guard('agent')->user();
        $my_id=$agent->id;
        Message::where(['agent_id'=>$my_id,'user_id'=>$user_id])->update(['agent_view'=>1]);

        $messages = Message::where(['agent_id'=>$my_id,'user_id'=>$user_id])->get();
        $website_lang=ManageText::all();
        return view('agent.message.message-box',compact('messages','website_lang'));

    }

    public function sendMessage(Request $request){
        $this->validate($request,[
            'receiver_id'=>'required',
            'message'=>'required'
        ]);


        $agent=Auth::guard('agent')->user();
        Message::create([
            'agent_id'=>$agent->id,
            'user_id'=>$request->receiver_id,
            'message'=>$request->message,
            'send_agent'=>$agent->id
        ]);

       return response()->json(['user_id'=>$request->receiver_id]);

    }
}
