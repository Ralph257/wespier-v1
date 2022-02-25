<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Appointment;

use App\User;
use App\Prescription;
use App\Schedule;
use App\Setting;
use App\ContactInformation;
use App\Day;
use App\PrescriptionFile;
use App\ManageText;
use App\ValidationText;
use App\NotificationText;
use Illuminate\Support\Facades\File;

class AgentAppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent');
    }

    public function todayAppointment(){
        $agent=Auth::guard('agent')->user();
        $day=date('l');
        $day=Day::where('day',$day)->first();
        $schedules=Schedule::where(['agent_id'=>$agent->id,'day_id'=>$day->id])->get();
        $today=Date('Y-m-d');
        $appointments=Appointment::where(['agent_id'=>Auth::guard('agent')->user()->id,'already_treated'=>0,'date'=>$today,'payment_status'=>1])->get();
        $website_lang=ManageText::all();


        $valid_lang=ValidationText::all();
        $schedule_error=$valid_lang->where('lang_key','req_schedule')->first()->custom_lang;
        return view('agent.appointment.today',compact('appointments','schedules','agent','website_lang','schedule_error'));
    }

    public function newAppointment(){
        $agent=Auth::guard('agent')->user();
        $schedules=Schedule::where('agent_id',$agent->id)->get();
        $appointments=Appointment::where(['agent_id'=>Auth::guard('agent')->user()->id,'already_treated'=>0,'payment_status'=>1])->get();
        $today=0;
        $website_lang=ManageText::all();

        $valid_lang=ValidationText::all();
        $from_date_error=$valid_lang->where('lang_key','req_from_date')->first()->custom_lang;
        $to_date_error=$valid_lang->where('lang_key','req_to_date')->first()->custom_lang;


        return view('agent.appointment.new',compact('appointments','schedules','agent','today','website_lang','to_date_error','from_date_error'));
    }

    public function allAppointment(){
        $appointments=Appointment::where(['agent_id'=>Auth::guard('agent')->user()->id])->orderBy('id','desc')->get();
        $website_lang=ManageText::all();
        return view('agent.appointment.all',compact('appointments','website_lang'));
    }

    public function showAppointment($id){
        $appointment=Appointment::find($id);
        $agent=Auth::guard('agent')->user();
        $setting=Setting::first();
        
        return view('agent.appointment.showAppointment',compact('appointment','agent','setting'));
    }

    public function editAppointment($id){
        $appointment=Appointment::find($id);
        $agent=Auth::guard('agent')->user();
        $setting=Setting::first();
        
        return view('agent.appointment.editAppointment',compact('appointment','agent','setting'));
    }

    public function meet($id){
        //dd($id);
        $agent=Auth::guard('agent')->user();
        $appointment=Appointment::find($id);
        if($appointment){
            if($appointment->agent_id==$agent->id){
                if($appointment->already_treated==0){
                        $setting=Setting::first();
                        $website_lang=ManageText::all();
                        return view('agent.appointment.prescription',compact('appointment','setting','agent','website_lang'));
                    }else{
                        $notify_lang=NotificationText::all();
                        $notification=$notify_lang->where('lang_key','appointment_not')->first()->custom_lang;
                        $notification=array('messege'=>$notification,'alert-type'=>'error');
                        return redirect()->back()->with($notification);
                    }
                }else{
                    $notify_lang=NotificationText::all();
                    $notification=$notify_lang->where('lang_key','wrong')->first()->custom_lang;
                    $notification=array('messege'=>$notification,'alert-type'=>'error');

                    return redirect()->route('agent.all.appointment')->with($notification);
                }
            }else{
                $notify_lang=NotificationText::all();
                $notification=$notify_lang->where('lang_key','wrong')->first()->custom_lang;
                $notification=array('messege'=>$notification,'alert-type'=>'error');

                return redirect()->route('agent.all.appointment')->with($notification);
            }
        
    }

    public function meetingStore(Request $request,$id){

            // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end



        $valid_lang=ValidationText::all();
        $rules = [
            'subject'=>'required',
            'description'=>'required',
        ];

        $customMessages = [
            'subject.required' => $valid_lang->where('lang_key','req_subject')->first()->custom_lang,
            'description.required' => $valid_lang->where('lang_key','req_des')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $appointment=Appointment::find($id);
        $appointment->already_treated=1;
        $appointment->stage='success';
        $appointment->save();

        $prescription=new Prescription();
        $prescription->appointment_id=$id;
        $prescription->subject=$request->subject;
        $prescription->description=$request->description;
        $prescription->save();

        if($request->documents){
            foreach($request->documents as $index => $row){
                $extention=$row->getClientOriginalExtension();
                $prescription_file= 'prescription-document-'.date('Y-m-d-h-i-s-').rand(999,9999).$index.'.'.$extention;
                $row->move(public_path().'/uploads/custom-images/',$prescription_file);
                $prescriptionFile=new PrescriptionFile();
                $prescriptionFile->prescription_id=$prescription->id;
                $prescriptionFile->file=$prescription_file;
                $prescriptionFile->save();
            }
        }


        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','create')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('agent.already-meet',$id)->with($notification);
    }

    public function editPresciption($id){
        $appointment=Appointment::find($id);
        $setting=Setting::first();
        $agent=Auth::guard('agent')->user();
        $website_lang=ManageText::all();
        return view('agent.appointment.edit-prescription',compact('appointment','setting','agent','website_lang'));
    }

    public function updatePresciption(Request $request,$id){
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'subject'=>'required',
            'description'=>'required',
        ];

        $customMessages = [
            'subject.required' => $valid_lang->where('lang_key','req_subject')->first()->custom_lang,
            'description.required' => $valid_lang->where('lang_key','req_des')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $appointment=Appointment::find($id);
        $prescription=Prescription::where('appointment_id',$id)->first();
        $prescription->appointment_id=$id;
        $prescription->subject=$request->subject;
        $prescription->description=$request->description;
        $prescription->save();

        if($request->documents){
            foreach($request->documents as $index => $row){
                $extention=$row->getClientOriginalExtension();
                $prescription_file= 'prescription-document-'.date('Y-m-d-h-i-s-').rand(999,9999).$index.'.'.$extention;
                $row->move(public_path().'/uploads/custom-images/',$prescription_file);
                $prescriptionFile=new PrescriptionFile();
                $prescriptionFile->prescription_id=$prescription->id;
                $prescriptionFile->file=$prescription_file;
                $prescriptionFile->save();
            }
        }


        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('agent.already-meet',$id)->with($notification);
    }

    public function deleteDoc($id){

        $doc=PrescriptionFile::find($id);
        $old_file= $doc->file;
        $doc->delete();
        $old_file= "public/uploads/custom-images/".$old_file;
        if(File::exists($old_file)) unlink($old_file);

        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','delete')->first()->custom_lang;
        return response()->json(['success'=>$notification]);
    }

    public function alreadyMeet($id){

        $agent=Auth::guard('agent')->user();
        $appointment=Appointment::find($id);
        if($appointment){
            if($appointment->agent_id==$agent->id){
                if($appointment->already_treated==1){
                    $setting=Setting::first();
                    $website_lang=ManageText::all();
                    return view('agent.appointment.meeted',compact('appointment','setting','agent','website_lang'));
                }else{
                    $notify_lang=NotificationText::all();
                    $notification=$notify_lang->where('lang_key','wrong')->first()->custom_lang;
                    $notification=array('messege'=>$notification,'alert-type'=>'error');

                    return redirect()->route('agent.all.appointment')->with($notification);
                }
            }else{
                $notify_lang=NotificationText::all();
                $notification=$notify_lang->where('lang_key','wrong')->first()->custom_lang;
                $notification=array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->route('agent.all.appointment')->with($notification);
            }
        }else{
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','wrong')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'error');

            return redirect()->route('agent.all.appointment')->with($notification);
        }
    }

    public function downloadFile($file){
        $filepath= public_path() . "/uploads/custom-images/".$file;
        return response()->download($filepath);
    }

    // search daily appointment using ajax
    public function searchAppointment(Request $request){
        $this->validate($request,[
            'agent_id'=>'required',
            'schedule_id'=>'required',
        ]);
        $date= Date('Y-m-d');
        $agent_id=$request->agent_id;
        $schedule_id=$request->schedule_id;
        $appointments=Appointment::where([
            'date'=>$date,
            'agent_id'=>$agent_id,
            'schedule_id'=>$schedule_id,
            'payment_status'=>1,
            'already_treated'=>0
        ])->get();
        $website_lang=ManageText::all();
        return view('agent.appointment.ajax-appointment',compact('appointments','website_lang'));

    }

    // search particular appointment using ajax
    public function searchParticulerAppointment(Request $request){
        $this->validate($request,[
            'from_date'=>'required',
            'to_date'=>'required',
            'agent_id'=>'required',
        ]);

        $from_date=date('Y-m-d',strtotime($request->from_date));
        $to_date=date('Y-m-d',strtotime($request->to_date));
        $appointments=Appointment::where(['agent_id'=>$request->agent_id,'payment_status'=>1,'already_treated'=>0])->get();
        $appointments=$appointments->whereBetween('date', array($from_date, $to_date));
        $website_lang=ManageText::all();
        return view('agent.appointment.ajax-particular-appointment',compact('appointments','website_lang'));
    }

    public function paymentHistory(){
        $last_one_month = \Carbon\Carbon::today()->subDays(30);
        $today=date('Y-m-d');
        $agent=Auth::guard('agent')->user();

        $appointments=Appointment::where([
            'agent_id'=>$agent->id,
            'payment_status'=>1
        ])->get();
        $appointments=$appointments->whereBetween('date', array($last_one_month, $today));

        $payment= $appointments->sum('appointment_fee');
        $appointment= $appointments->count();
        $currency=Setting::first();

        $website_lang=ManageText::all();

        $valid_lang=ValidationText::all();
        $from_date_error=$valid_lang->where('lang_key','req_from_date')->first()->custom_lang;

        return view('agent.payment.index',compact('appointments','payment','appointment','agent','currency','website_lang','from_date_error'));

    }

    public function searchPaymentHistory(Request $request){
        $from_date=date('Y-m-d',strtotime($request->from_date));
        $to_date=date('Y-m-d',strtotime($request->to_date));
        $agent=Auth::guard('agent')->user();
        $appointments=Appointment::where([
            'agent_id'=>$agent->id,
            'payment_status'=>1
        ])->get();
        $appointments=$appointments->whereBetween('date', array($from_date, $to_date));
        $payment= $appointments->sum('appointment_fee');
        $appointment= $appointments->count();
        $currency=Setting::first();
        $website_lang=ManageText::all();
        return view('agent.payment.ajax-payment',compact('appointments','payment','appointment','agent','currency','website_lang'));
    }

    public function deleteAppointmentPrescribe($id){
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end
        Prescribe::destroy($id);
        return response()->json(['success'=>'delete success']);
    }
}
