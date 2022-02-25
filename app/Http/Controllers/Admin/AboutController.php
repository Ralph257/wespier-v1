<?php

namespace App\Http\Controllers\Admin;

use App\About;
use App\ManageText;
use App\ValidationText;
use App\NotificationText;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class AboutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $about=About::first();
        if($about){
            $website_lang=ManageText::all();
            return view('admin.about.edit',compact('about','website_lang'));
        }

    }





    public function edit(About $about)
    {
        return back();
    }

    public function updateAbout(Request $request,$id){

        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'about_description'=>'required'
        ];
        $customMessages = [
            'about_description.required' => $valid_lang->where('lang_key','req_about_des')->first()->custom_lang,
        ];
        $this->validate($request, $rules, $customMessages);


        if($request->file('image')){
            //    manage about image
            $about_image=$request->image;
            $about_extention=$about_image->getClientOriginalExtension();
            $about_name= 'about-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$about_extention;
            $about_path='uploads/website-images/'.$about_name;

            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                Image::make($about_image)
                ->resize(900,null,function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->crop(425,425)
                ->save($about_path);
            }else{
                Image::make($about_image)
                ->resize(900,null,function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->crop(425,425)
                ->save('public/'.$about_path);
            }



            About::where('id',$id)->update([
                'about_image'=>$about_path,
            ]);
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($request->old_about_image))unlink($request->old_about_image);
            }else{
                if(File::exists('public/'.$request->old_about_image))unlink('public/'.$request->old_about_image);
            }

        }
        if($request->file('background_image')){
            //    manage about image
            $background_image=$request->background_image;
            $about_extention=$background_image->getClientOriginalExtension();
            $about_name= 'about-background-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$about_extention;
            $bg_path='uploads/website-images/'.$about_name;

            $root_path=request()->getHost();
                if($root_path=='127.0.0.1'){
                    Image::make($background_image)
                    ->resize(900,null,function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->crop(480,480)
                    ->save($bg_path);
                }else{
                    Image::make($background_image)
                    ->resize(900,null,function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->crop(480,480)
                    ->save('public/'.$bg_path);
                }



            About::where('id',$id)->update([
                'background_image'=>$bg_path,
            ]);

            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($request->old_background_image))unlink($request->old_background_image);
            }else{
                if(File::exists('public/'.$request->old_background_image))unlink('public/'.$request->old_background_image);
            }

        }

        About::where('id',$id)->update([
            'about_description'=>$request->about_description
        ]);


        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.about.index')->with($notification);
    }


    public function updateMission(Request $request,$id){

        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'mission_description'=>'required',
        ];
        $customMessages = [
            'mission_description.required' => $valid_lang->where('lang_key','req_mission_des')->first()->custom_lang,
        ];
        $this->validate($request, $rules, $customMessages);


        if($request->file('image')){
            //    manage mission image
            $mission_image=$request->image;
            $mission_extention=$mission_image->getClientOriginalExtension();
            $mission_name= 'mission-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$mission_extention;
            $mission_path='uploads/website-images/'.$mission_name;

            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                Image::make($mission_image)
                ->resize(900,null,function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->crop(525,452)
                ->save($mission_path);
            }else{
                Image::make($mission_image)
                ->resize(900,null,function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->crop(525,452)
                ->save('public/'.$mission_path);
            }



            About::where('id',$id)->update([
                'mission_image'=>$mission_path,
                'mission_description'=>$request->mission_description
            ]);
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($request->old_mission_image))unlink($request->old_mission_image);
            }else{
                if(File::exists('public/'.$request->old_mission_image))unlink('public/'.$request->old_mission_image);
            }


            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'success');

            return redirect()->route('admin.about.index')->with($notification);
        }else {
            About::where('id',$id)->update([
                'mission_description'=>$request->mission_description
            ]);
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'success');

            return redirect()->route('admin.about.index')->with($notification);
        }
    }

    public function updateVision(Request $request,$id){

        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'vision_description'=>'required',
        ];
        $customMessages = [
            'vision_description.required' => $valid_lang->where('lang_key','req_vision_des')->first()->custom_lang,
        ];
        $this->validate($request, $rules, $customMessages);

        if($request->file('image')){
            //    manage mission image
            $vision_image=$request->image;
            $vision_extention=$vision_image->getClientOriginalExtension();
            $vision_name= 'mission-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$vision_extention;
            $vision_path='uploads/website-images/'.$vision_name;


            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                Image::make($vision_image)
                ->resize(900,null,function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->crop(525,452)
                ->save($vision_path);
            }else{
                Image::make($vision_image)
                ->resize(900,null,function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->crop(525,452)
                ->save('public/'.$vision_path);
            }


            About::where('id',$id)->update([
                'vision_image'=>$vision_path,
                'vision_description'=>$request->vision_description
            ]);

            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($request->old_vision_image))unlink($request->old_vision_image);
            }else{
                if(File::exists('public/'.$request->old_vision_image))unlink('public/'.$request->old_vision_image);
            }

            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'success');

            return redirect()->route('admin.about.index')->with($notification);
        }else {
            About::where('id',$id)->update([
                'vision_description'=>$request->vision_description
            ]);
            $notify_lang=NotificationText::all();
            $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
            $notification=array('messege'=>$notification,'alert-type'=>'success');

            return redirect()->route('admin.about.index')->with($notification);
        }
    }

}
