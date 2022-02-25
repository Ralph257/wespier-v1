<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Day;
use App\Schedule;
use App\Agent;
use App\Appointment;
use App\Lawyer;
use App\Department;
use App\Leave;
use App\ManageText;
use App\NotificationText;
use Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Gloudemans\Shoppingcart\Facades\Cart as FacadesCart;

class AppointmentController extends Controller
{

    public function getAppointment(Request $request){

        $website_lang=ManageText::all();
        $leave=Leave::where(['agent_id'=>$request->agent_id,'date'=>$request->date])->count();
        $html="";
        if($leave ==0){
            $agent_id=$request->agent_id;
            $day=date('l',strtotime($request->date));
            $day=Day::where('day',$day)->first();
            $day=$day->id;
            $schedules=Schedule::where(['agent_id'=>$agent_id,'day_id'=>$day])->get();
            if($schedules->count() !=0){
                foreach($schedules as $index=> $schedule){
                    $html.='<option value="'.$schedule->id.'">'.strtoupper($schedule->start_time).'-'.strtoupper($schedule->end_time).'</option>';
                }
                return response()->json(['success'=>$html]);
            }else{
                $html="<h4 class='text-danger'>".$website_lang->where('lang_key','schedule_not_found')->first()->custom_lang."</h4>";
                return response()->json(['error'=>$html]);
            }
        }else{
            $html="<h4 class='text-danger'>.$website_lang->where('lang_key','agent_not_found')->first()->custom_lang.</h4>";
            return response()->json(['error'=>$html]);
        }
    }

    public function getDepartmentAgent($id){
        $website_lang=ManageText::all();
        $agents=Agent::where(['department_id'=>$id,'status'=>1])->get();
        $html='<option value="">'.$website_lang->where('lang_key','select_agent')->first()->custom_lang.'</option>';
        if($agents){
            foreach($agents as $agent){
                $html.='<option value="'.$agent->id.'">'.ucfirst($agent->name).'</option>';
            }
        }
        return response()->json($html);
    }

    public function getDepartmentLawyer($id){
        $website_lang=ManageText::all();
        $lawyers=Lawyer::where(['department_id'=>$id,'status'=>1])->get();
        $html='<option value="">'.$website_lang->where('lang_key','select_lawyer')->first()->custom_lang.'</option>';
        if($lawyers){
            foreach($lawyers as $lawyer){
                $html.='<option value="'.$lawyer->id.'">'.ucfirst($lawyer->name).'</option>';
            }
        }
        return response()->json($html);
    }

    public function createGuessAppointment(Request $request){
//dd($request);
        $guestname = $request->guestname;
        $email = $request->email;
        $phone = $request->phone;
        $agent_id=$request->agent_id;
        $department_id=$request->department_id;
        $date=$request->date;
        $schedule_id=$request->schedule_id;

        $schedule=Schedule::find($schedule_id);
        $agent=Agent::find($agent_id);
        $department=Department::find($department_id);

        $appointment = new Appointment();

        $appointment->guestname=$guestname;
        $appointment->email=$email;
        $appointment->phone=$phone;
        $appointment->order_id=uniqid();
        $appointment->agent_id=$agent_id;
        $appointment->department=$department->name;
        $appointment->location=$agent->location->location;
        $appointment->user_id= 0;
        $appointment->date=$date;
        $appointment->time=$schedule->start_time.'-'.$schedule->end_time;
        $appointment->schedule_id=$schedule->id;
        $appointment->day_id=$schedule->day_id;
        $appointment->save();


        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','create_appointment')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return view('client.guest-appointment')->with($notification);;
    }

    public function createAppointment(Request $request){

        $agent_id=$request->agent_id;
        $department_id=$request->department_id;
        $date=$request->date;
        $schedule_id=$request->schedule_id;

        $schedule=Schedule::find($schedule_id);
        $agent=Agent::find($agent_id);
        $department=Department::find($department_id);

        $data['id']=rand(22,222);// it is mendetory
        $data['name']=$agent->name;
        $data['qty']=1;
        $data['price']=$agent->fee;
        $data['weight']=0; // it is mendatory
        $data['options']['agent_id']=$agent_id;
        $data['options']['department']=$department->name;
        $data['options']['location']=$agent->location->location;
        $data['options']['date']=$date;
        $data['options']['time']=$schedule->start_time.'-'.$schedule->end_time;
        $data['options']['schedule_id']=$schedule->id;
        $data['options']['day_id']=$schedule->day_id;
        cart::add($data);


        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','create_appointment')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('client.payment')->with($notification);
    }

    public function removeAppointment($id){
        Cart::remove($id);

        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','delete')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }
}
