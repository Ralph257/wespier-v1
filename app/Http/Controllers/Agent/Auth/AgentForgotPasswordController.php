<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\User;
use App\Agent;
use App\Mail\ForgetPassword;
use App\Mail\DoctorForgetPassword;
use Str;
use Mail;
use Hash;
use Auth;
use App\BannerImage;
use App\ManageText;
use App\ValidationText;
use App\EmailTemplate;
use App\Helpers\MailHelper;
use App\NotificationText;
class AgentForgotPasswordController extends Controller
{
   public function forgetPassword(){
        $image=BannerImage::first();
        $website_lang=ManageText::all();
       return view('agent.auth.forget-password',compact('image','website_lang'));
   }

   public function sendForgetEmail(Request $request){

    // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'email'=>'required'
        ];

        $customMessages = [
            'email.required' => $valid_lang->where('lang_key','req_email')->first()->custom_lang,
        ];
        $this->validate($request, $rules, $customMessages);

        $agent=Agent::where('email',$request->email)->first();
        if($agent){
            $agent->forget_password_token=Str::random(100);
            $agent->save();

            $template=EmailTemplate::where('id',1)->first();
            $message=$template->description;
            $subject=$template->subject;
            $message=str_replace('{{name}}',$agent->name,$message);

            $website_lang=ManageText::all();
            $reset_pass_text=$website_lang->where('lang_key','reset_password')->first()->custom_lang;

            MailHelper::setMailConfig();
            Mail::to($agent->email)->send(new DoctorForgetPassword($agent,$message,$subject,$reset_pass_text));

            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','forget_pass')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'success');

            return back()->with($notification);

        }else {

            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','email_not_exist')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return back()->with($notification);
        }

   }

   public function resetPassword($token){
        $agent=Agent::where('forget_password_token',$token)->first();
        $image=BannerImage::first();
        if($agent){
            $website_lang=ManageText::all();
            return view('agent.auth.reset-password',compact('agent','token','image','website_lang'));
        }else{
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','invalid_token')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return Redirect()->route('agent.forget.password')->with($notification);
        }
   }


   public function storeResetData(Request $request,$token){
        $valid_lang=ValidationText::all();
        $rules = [
            'email'=>'required',
            'password'=>'required|confirmed'
        ];

        $customMessages = [
            'email.required' => $valid_lang->where('lang_key','req_email')->first()->custom_lang,
            'password.required' => $valid_lang->where('lang_key','req_pass')->first()->custom_lang,
            'password.confirmed' => $valid_lang->where('lang_key','confirm_pass')->first()->custom_lang,
        ];
        $this->validate($request, $rules, $customMessages);

        $agent=Agent::where('forget_password_token',$token)->first();
        if($agent->email==$request->email){
            $agent->password=Hash::make($request->password);
            $agent->forget_password_token=null;
            $agent->save();

            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','reset_pass')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return Redirect()->route('agent.login')->with($notification);

        }else {
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','email_not_exist')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return back()->with($notification);
        }
   }


}
