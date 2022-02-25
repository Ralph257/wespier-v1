<?php
namespace App\Http\Controllers\Admin;

use App\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Department;
use App\Location;
use App\Mail\AgentLoginInformation;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Setting;
use App\EmailTemplate;
use App\Schedule;
use App\Message;
use App\Appointment;
use App\Helpers\MailHelper;
use App\MeetingHistory;
use App\ZoomMeeting;
use App\ZoomCredential;
use App\ManageText;
use App\ValidationText;
use App\NotificationText;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {

        $agents=Agent::all();
        $currency=Setting::first();
        $schedules=Schedule::all();
        $messages=Message::all();
        $appointments=Appointment::all();
        $website_lang=ManageText::all();
        return view('admin.agent.index',compact('agents','currency','schedules','messages','appointments','website_lang'));
    }


    public function create()
    {
        $departments=Department::orderBy('name','asc')->get();
        $locations=Location::orderBy('location','asc')->get();
        $website_lang=ManageText::all();
        return view('admin.agent.create',compact('departments','locations','website_lang'));
    }


    public function store(Request $request)
    {
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'name'=>'required',
            'email'=>'required|unique:agents', 
            'phone'=>'required',
            'password'=>'required',
            'designations'=>'required',
            'image'=>'required',
            'appointment_fee'=>'required',
            'department'=>'required',
            'location'=>'required',
        ];
        $customMessages = [
            'name.required' => $valid_lang->where('lang_key','req_name')->first()->custom_lang,
            'email.required' => $valid_lang->where('lang_key','req_email')->first()->custom_lang,
            'email.unique' => $valid_lang->where('lang_key','unique_email')->first()->custom_lang,
            'phone.required' => $valid_lang->where('lang_key','req_phone')->first()->custom_lang,
            'password.required' => $valid_lang->where('lang_key','req_pass')->first()->custom_lang,
            'designations.required' => $valid_lang->where('lang_key','req_designation')->first()->custom_lang,
            'image.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang,
            'appointment_fee.required' => $valid_lang->where('lang_key','req_fee')->first()->custom_lang,
            'department.required' => $valid_lang->where('lang_key','req_department')->first()->custom_lang,
            'location.required' => $valid_lang->where('lang_key','req_location')->first()->custom_lang,
        ];
        $this->validate($request, $rules, $customMessages);


        $image=$request->image;
        $extention=$image->getClientOriginalExtension();
        $name= 'agent-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/custom-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(500,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(500,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }


        $agent=Agent::create([
                'name'=>$request->name,
                'slug'=>Str::slug($request->name),
                'email'=>$request->email,
                'phone'=>$request->phone,
                'facebook'=>$request->facebook,
                'youtube'=>$request->youtube,
                'twitter'=>$request->twitter,
                'linkedin'=>$request->linkedin,
                'pinterest'=>$request->pinterest,
                'linktopage'=>$request->linktopage,
                'password'=>Hash::make($request->password),
                'designations'=>$request->designations,
                'image'=>$image_path,
                'fee'=>$request->appointment_fee,
                'department_id'=>$request->department,
                'location_id'=>$request->location,
                'seo_title'=>$request->seo_title,
                'seo_description'=>$request->seo_description,
                'about'=>$request->about,
                'address'=>$request->address,
                'educations'=>$request->educations,
                'experience'=>$request->experiences,
                'qualifications'=>$request->qualifications,
                'status'=>$request->status,
                'show_homepage'=>$request->show_homepage
            ]);

            // Send Email to Agent after account is created // Removed for now //

        // $website_lang=ManageText::all();
        // $login_here_text=$website_lang->where('lang_key','agent_login_here')->first()->custom_lang;

        // $template=EmailTemplate::where('id',9)->first();
        // $message=$template->description;
        // $subject=$template->subject;
        // $message=str_replace('{{agent_name}}',$agent->name,$message);
        // $message=str_replace('{{email}}',$agent->email,$message);
        // $message=str_replace('{{password}}',$request->password,$message);
        // MailHelper::setMailConfig();
        // Mail::to($agent->email)->send(new AgentLoginInformation($message,$subject,$login_here_text));
        // $notify_lang=NotificationText::all();
        // $notification=$notify_lang->where('lang_key','create')->first()->custom_lang;
        // $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }


    public function edit(Agent $agent)
    {

        $departments=Department::orderBy('name','asc')->get();
        $locations=Location::orderBy('location','asc')->get();
        $website_lang=ManageText::all();
        return view('admin.agent.edit',compact('departments','locations','agent','website_lang'));
    }


    public function update(Request $request, Agent $agent)
    {
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'name'=>'required',
            'email'=>'required|unique:agents,email,'.$agent->id,
            'phone'=>'required',
            'designations'=>'required',
            'appointment_fee'=>'required',
            'department'=>'required',
            'location'=>'required',
            'status'=>'required',
            'show_homepage'=>'required'
        ];
        $customMessages = [
            'name.required' => $valid_lang->where('lang_key','req_name')->first()->custom_lang,
            'email.required' => $valid_lang->where('lang_key','req_email')->first()->custom_lang,
            'email.unique' => $valid_lang->where('lang_key','unique_email')->first()->custom_lang,
            'phone.required' => $valid_lang->where('lang_key','req_phone')->first()->custom_lang,
            'designations.required' => $valid_lang->where('lang_key','req_designation')->first()->custom_lang,
            'appointment_fee.required' => $valid_lang->where('lang_key','req_fee')->first()->custom_lang,
            'department.required' => $valid_lang->where('lang_key','req_department')->first()->custom_lang,
            'location.required' => $valid_lang->where('lang_key','req_location')->first()->custom_lang,
        ];
        $this->validate($request, $rules, $customMessages);





        // upload new image
        $image_path=$agent->image;
        if($request->image){
            $old_image=$agent->image;
            $image=$request->image;
            $extention=$image->getClientOriginalExtension();
            $name= 'agent-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_path='uploads/custom-images/'.$name;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                Image::make($image)
                ->resize(500,null,function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($image_path);

                if(File::exists($old_image))unlink($old_image);
            }else{
                Image::make($image)
                ->resize(500,null,function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save('public/'.$image_path);

                if(File::exists('public/'.$old_image))unlink('public/'.$old_image);
            }

        }

        Agent::where('id',$agent->id)->update([
            'name'=>$request->name,
            'slug'=>Str::slug($request->name),
            'email'=>$request->email,
            'phone'=>$request->phone,
            'facebook'=>$request->facebook,
            'youtube'=>$request->youtube,
            'twitter'=>$request->twitter,
            'linkedin'=>$request->linkedin,
            'pinterest'=>$request->pinterest,
            'linktopage'=>$request->linktopage,
            'designations'=>$request->designations,
            'image'=>$image_path,
            'fee'=>$request->appointment_fee,
            'department_id'=>$request->department,
            'location_id'=>$request->location,
            'seo_title'=>$request->seo_title,
            'seo_description'=>$request->seo_description,
            'about'=>$request->about,
            'address'=>$request->address,
            'educations'=>$request->educations,
            'experience'=>$request->experiences,
            'qualifications'=>$request->qualifications,
            'status'=>$request->status,
            'show_homepage'=>$request->show_homepage
        ]);

        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('admin.agent.index')->with($notification);

    }


    public function destroy(Agent $agent)
    {
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end
        $agent_id=$agent->id;
        $old_image=$agent->image;
        Message::where('agent_id',$agent_id)->delete();
        MeetingHistory::where('agent_id',$agent_id)->delete();
        ZoomMeeting::where('agent_id',$agent_id)->delete();
        ZoomCredential::where('agent_id',$agent_id)->delete();
        $agent->delete();
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            if(File::exists($old_image))unlink($old_image);
        }else{
            if(File::exists('public/'.$old_image))unlink('public/'.$old_image);
        }

        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','delete')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('admin.agent.index')->with($notification);
    }

     // change Agent status
     public function changeStatus($id){
        $agent=Agent::find($id);
        if($agent->status==1){
            $agent->status=0;
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','inactive')->first()->custom_lang;
            $message=$notification;
        }else{
            $agent->status=1;
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','active')->first()->custom_lang;
            $message=$notification;
        }
        $agent->save();
        return response()->json($message);

    }
}
