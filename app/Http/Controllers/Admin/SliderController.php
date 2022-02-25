<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Slider;
use App\ManageText;
use App\ValidationText;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\NotificationText;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $sliders=Slider::all();
        $website_lang=ManageText::all();
        return view('admin.slider.index',compact('sliders','website_lang'));
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
            'image'=>'required'
        ];

        $customMessages = [
            'image.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);


        $image=$request->image;
        $extention=$image->getClientOriginalExtension();
        $name= 'slider-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;

        $image_path='uploads/website-images/'.$name;


        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,690)
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,690)
            ->save('public/'.$image_path);
        }

        Slider::create([
            'image'=>$image_path,
            'status'=>$request->status
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','create')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }


    public function destroy(Slider $slider)
    {

                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $oldImage=$slider->image;
        $slider->delete();

        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            if(File::exists($oldImage))unlink($oldImage);
        }else{
            if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
        }

        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','delete')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }


     // manage image status
     public function changeStatus($id){
        $slider=Slider::find($id);
        if($slider->status==1){
            $slider->status=0;
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','inactive')->first()->custom_lang;
            $message=$notification;
        }else{
            $slider->status=1;
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','active')->first()->custom_lang;
            $message=$notification;
        }
        $slider->save();
        return response()->json($message);

    }
}
