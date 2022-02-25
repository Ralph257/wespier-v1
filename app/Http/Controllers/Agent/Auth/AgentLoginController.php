<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Admin;
use App\Agent;
use App\BannerImage;
use App\ManageText;
use App\ValidationText;
use App\NotificationText;
use Hash;
class AgentLoginController extends Controller
{


    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/agent/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:agent')->except('agentLogout');
    }


    public function agentLoginForm(){
        $image=BannerImage::first();
        $website_lang=ManageText::all();
        return view('agent.auth.login',compact('image','website_lang'));
    }

    public function storeLoginInfo(Request $request){
        $valid_lang=ValidationText::all();
        $rules = [
            'email'=>'required|email',
            'password'=>'required',
        ];

        $customMessages = [
            'email.required' => $valid_lang->where('lang_key','req_email')->first()->custom_lang,
            'password.required' => $valid_lang->where('lang_key','req_pass')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);



        $credential=[
            'email'=> $request->email,
            'password'=> $request->password
        ];

        $isAgent=Agent::where('email',$request->email)->first();
        if($isAgent){
            if(Hash::check($request->password,$isAgent->password)){
                if(Auth::guard('agent')->attempt($credential,$request->remember)){

                    $notify_lang=NotificationText::all();
                    $notification=$notify_lang->where('lang_key','login')->first()->custom_lang;
                    $notification=array('messege'=>$notification,'alert-type'=>'success');

                    return Redirect()->intended(route('agent.dashboard'))->with($notification);
                }

                $notify_lang=NotificationText::all();
                $notification=$notify_lang->where('lang_key','wrong')->first()->custom_lang;
                $notification=array('messege'=>$notification,'alert-type'=>'error');

                return Redirect()->back()->withInput($request->only('email,remember'))->with($notification);
            }else{

                $notify_lang=NotificationText::all();
                $notification=$notify_lang->where('lang_key','credential')->first()->custom_lang;
                $notification=array('messege'=>$notification,'alert-type'=>'error');
                return back()->with($notification);
            }

        }else{
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','credential')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return back()->with($notification);
        }



    }

    public function agentLogout(){
        Auth::guard('agent')->logout();
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','logout')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('agent.login')->with($notification);
    }

}
