<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cart;
use Session;
use Stripe;
use App\Day;
use App\Schedule;
use App\Lawyer;
use App\Department;
use App\Appointment;
use App\PaymentAccount;
use Auth;
use App\Order;
use Str;
use App\Setting;
use App\BannerImage;
use App\ManageText;
use App\ValidationText;
use App\Navigation;
use App\Mail\OrderConfirmation;
use Mail;
use App\EmailTemplate;
use App\NotificationText;
use App\Helpers\MailHelper;
use App\Razorpay;
use Razorpay\Api\Api;
use Exception;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function payment(){
        $currency=Setting::first();
        $user=Auth::guard('web')->user();
        $notify=NotificationText::first();
        if($user->ready_for_appointment==1){
            $appointments=Cart::content();
            $stripe=PaymentAccount::first();
            $banner=BannerImage::first();
            $navigation=Navigation::first();
            $website_lang=ManageText::all();
            $razorpay=Razorpay::first();
            $paymentSetting=$stripe;
            return view('client.profile.payment',compact('appointments','stripe','currency','banner','navigation','website_lang','paymentSetting','razorpay'));
        }else{
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','fillup_profile')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('client.account')->with($notification);
        };

    }


    public function stripePayment(Request $request){
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $user=Auth::guard('web')->user();
        $stripe=PaymentAccount::first();
        $currency=Setting::first();
        Stripe\Stripe::setApiKey($stripe->stripe_secret);
        $result=Stripe\Charge::create ([
                "amount" => Cart::pricetotal() * 100,
                "currency" => $currency->currency_name,
                "source" => $request->stripeToken,
                "description" => "Doctor appointment"
        ]);

        // insert order
        $order= Order::create([
            'user_id'=>$user->id,
            'order_id'=>'#'.date('Yms').rand(9,99),
            'total_payment'=>Cart::pricetotal(),
            'appointment_qty'=>Cart::count(),
            'payment_method'=>'Stripe',
            'payment_status'=>1,
            'last4'=>substr($request->card_digit,-4),
            'payment_transaction_id'=>$result->balance_transaction
        ]);
        $order_details="";
        foreach(Cart::content() as $item){
            Appointment::create([
                'order_id'=>$order->id,
                'lawyer_id'=>$item->options->lawyer_id,
                'user_id'=>$user->id,
                'day_id'=>$item->options->day_id,
                'schedule_id'=>$item->options->schedule_id,
                'date'=>date('Y-m-d',strtotime($item->options->date)),
                'appointment_fee'=>$item->price,
                'payment_status'=>1,
                'payment_transaction_id'=>$result->balance_transaction,
                'payment_method'=>'Stripe',

            ]);

            $lawyer=Lawyer::find($item->options->lawyer_id);
            $order_details.='Lawyer: '. $lawyer->name. '<br>';
            $order_details.='Phone: '. $lawyer->phone .'<br>';
            $order_details.='Schedule: '.$item->options->time .'<br>';
            $order_details.='Date: '.$item->options->date.'<br>';
        }

        Cart::destroy();

         // send email
         $template=EmailTemplate::where('id',6)->first();
         $message=$template->description;
         $subject=$template->subject;
         $message=str_replace('{{client_name}}',$user->name,$message);
         $message=str_replace('{{orderId}}', $order->order_id ,$message);
         $message=str_replace('{{payment_method}}','Stripe',$message);
         $total_amount=$currency->currency_icon. $order->total_payment;
         $message=str_replace('{{amount}}',$total_amount,$message);
         $message=str_replace('{{order_details}}',$order_details,$message);
         MailHelper::setMailConfig();
         Mail::to($user->email)->send(new OrderConfirmation($message,$subject));


        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','payment_success')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('client.order')->with($notification);
    }
    public function bankPayment(Request $request){

        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'description'=>'required'
        ];

        $customMessages = [
            'description.required' => $valid_lang->where('lang_key','req_des')->first()->custom_lang,
        ];
        $this->validate($request, $rules, $customMessages);

        $currency=Setting::first();
        $user=Auth::guard('web')->user();

        // insert order
        $order= Order::create([
            'user_id'=>$user->id,
            'order_id'=>'#'.date('Yms').rand(9,99),
            'total_payment'=>Cart::pricetotal(),
            'appointment_qty'=>Cart::count(),
            'payment_method'=>'Bank Transfer',
            'payment_status'=>0,
            'payment_transaction_id'=>null,
            'payment_description'=>$request->description
        ]);

        $order_details="";
        foreach(Cart::content() as $item){
            Appointment::create([
                'order_id'=>$order->id,
                'lawyer_id'=>$item->options->lawyer_id,
                'user_id'=>$user->id,
                'day_id'=>$item->options->day_id,
                'schedule_id'=>$item->options->schedule_id,
                'date'=>date('Y-m-d',strtotime($item->options->date)),
                'appointment_fee'=>$item->price,
                'payment_status'=>0,
                'payment_transaction_id'=>null,
                'payment_method'=>'Bank Transfer',
                'payment_description'=>$request->description,
            ]);

            $lawyer=Lawyer::find($item->options->lawyer_id);
            $order_details.='Lawyer: '. $lawyer->name. '<br>';
            $order_details.='Phone: '. $lawyer->phone .'<br>';
            $order_details.='Schedule: '.$item->options->time .'<br>';
            $order_details.='Date: '.$item->options->date.'<br>';

        }

        Cart::destroy();

        // send email
        $template=EmailTemplate::where('id',6)->first();
        $message=$template->description;
        $subject=$template->subject;
        $message=str_replace('{{client_name}}',$user->name,$message);
        $message=str_replace('{{orderId}}', $order->order_id ,$message);
        $message=str_replace('{{payment_method}}','Bank Transfer',$message);
        $total_amount=$currency->currency_icon. $order->total_payment;
        $message=str_replace('{{amount}}',$total_amount,$message);
        $message=str_replace('{{order_details}}',$order_details,$message);
        MailHelper::setMailConfig();
        Mail::to($user->email)->send(new OrderConfirmation($message,$subject));


        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','payment_success')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('client.order')->with($notification);


    }


    public function razorPay(Request $request){
         // project demo mode check
         if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $user=Auth::guard('web')->user();
        $stripe=PaymentAccount::first();
        $currency=Setting::first();

        $razorpay=Razorpay::first();
        $input = $request->all();
        $api = new Api($razorpay->razorpay_key,$razorpay->secret_key);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                $payId=$response->id;
                        // insert order
                $order= Order::create([
                    'user_id'=>$user->id,
                    'order_id'=>'#'.date('Yms').rand(9,99),
                    'total_payment'=>Cart::pricetotal(),
                    'appointment_qty'=>Cart::count(),
                    'payment_method'=>'Razorpay',
                    'payment_status'=>1,
                    'payment_transaction_id'=>$payId
                ]);
                $order_details="";
                foreach(Cart::content() as $item){
                    Appointment::create([
                        'order_id'=>$order->id,
                        'lawyer_id'=>$item->options->lawyer_id,
                        'user_id'=>$user->id,
                        'day_id'=>$item->options->day_id,
                        'schedule_id'=>$item->options->schedule_id,
                        'date'=>date('Y-m-d',strtotime($item->options->date)),
                        'appointment_fee'=>$item->price,
                        'payment_status'=>1,
                        'payment_transaction_id'=>$payId,
                        'payment_method'=>'Razorpay',

                    ]);

                    $lawyer=Lawyer::find($item->options->lawyer_id);
                    $order_details.='Lawyer: '. $lawyer->name. '<br>';
                    $order_details.='Phone: '. $lawyer->phone .'<br>';
                    $order_details.='Schedule: '.$item->options->time .'<br>';
                    $order_details.='Date: '.$item->options->date.'<br>';
                }

                Cart::destroy();

                // send email
                $template=EmailTemplate::where('id',6)->first();
                $message=$template->description;
                $subject=$template->subject;
                $message=str_replace('{{client_name}}',$user->name,$message);
                $message=str_replace('{{orderId}}', $order->order_id ,$message);
                $message=str_replace('{{payment_method}}','Razorpay',$message);
                $total_amount=$currency->currency_icon. $order->total_payment;
                $message=str_replace('{{amount}}',$total_amount,$message);
                $message=str_replace('{{order_details}}',$order_details,$message);
                MailHelper::setMailConfig();
                Mail::to($user->email)->send(new OrderConfirmation($message,$subject));


                $notify_lang=NotificationText::all();
                $notification=$notify_lang->where('lang_key','payment_success')->first()->custom_lang;
                $notification=array('messege'=>$notification,'alert-type'=>'success');

                return redirect()->route('client.order')->with($notification);

            }catch (Exception $e) {

                $notify_lang=NotificationText::all();
                $notification=$notify_lang->where('lang_key','payment_failed')->first()->custom_lang;
                $notification=array('messege'=>$notification,'alert-type'=>'error');

                return redirect()->route('client.payment')->with($notification);
            }
        }
    }
}
