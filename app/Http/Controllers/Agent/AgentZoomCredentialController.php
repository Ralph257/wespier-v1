<?php

namespace App\Http\Controllers\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\AgentZoomCredential;
use App\ManageText;
use App\ValidationText;
use App\NotificationText;

class AgentZoomCredentialController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:agent');
    }

    public function index(){
        $agent=Auth::guard('agent')->user();

        $credential=AgentZoomCredential::where('agent_id',$agent->id)->first();
        $website_lang=ManageText::all();
        return view('agent.zoom.setting.index',compact('credential','website_lang'));
    }

    public function store(Request $request){
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'zoom_api_key'=>'required',
            'zoom_api_secret'=>'required',
        ];

        $customMessages = [
            'zoom_api_key.required' => $valid_lang->where('lang_key','req_zoom_api_key')->first()->custom_lang,
            'zoom_api_secret.required' => $valid_lang->where('lang_key','req_zoom_api_secret')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $agent=Auth::guard('agent')->user();
        $credential=new AgentZoomCredential();
        $credential->agent_id=$agent->id;
        $credential->zoom_api_key=$request->zoom_api_key;
        $credential->zoom_api_secret=$request->zoom_api_secret;
        $credential->save();


        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return redirect()->back()->with($notification);
    }

    public function update(Request $request,$id){

        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end
        $valid_lang=ValidationText::all();
        $rules = [
            'zoom_api_key'=>'required',
            'zoom_api_secret'=>'required',
        ];

        $customMessages = [
            'zoom_api_key.required' => $valid_lang->where('lang_key','req_zoom_api_key')->first()->custom_lang,
            'zoom_api_secret.required' => $valid_lang->where('lang_key','req_zoom_api_secret')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $agent=Auth::guard('agent')->user();

        $credential=AgentZoomCredential::find($id);
        $credential->agent_id=$agent->id;
        $credential->zoom_api_key=$request->zoom_api_key;
        $credential->zoom_api_secret=$request->zoom_api_secret;
        $credential->save();


        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return redirect()->back()->with($notification);
    }
}
