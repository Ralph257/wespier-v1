<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BannerImage;
use App\ManageText;
use App\ValidationText;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\NotificationText;

class BannerImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(){
        $banner=BannerImage::first();
        $website_lang=ManageText::all();
        return view('admin.banner-image.index',compact('banner','website_lang','website_lang'));
    }

    public function aboutBanner(Request $request){
        // project demo mode check
    if(env('PROJECT_MODE')==0){
        $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
        return redirect()->back()->with($notification);
    }
    // end



        $valid_lang=ValidationText::all();
        $rules = [
            'about_us'=>'required',

        ];
        $customMessages = [
            'about_us.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->about_us){
            $oldImage=$banner->about_us;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->about_us;
        $extention=$image->getClientOriginalExtension();
        $name= 'about-us-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;

        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }



        BannerImage::where('id',$banner->id)->update([
            'about_us'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);

    }
    public function aboutUsBg(Request $request){
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'about_us_bg'=>'required',

        ];
        $customMessages = [
            'about_us_bg.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);


        $banner=BannerImage::first();
        if($banner->about_us_bg){
            $oldImage=$banner->about_us_bg;
            $root_path=request()->getHost();
                if($root_path=='127.0.0.1'){
                    if(File::exists($oldImage))unlink($oldImage);
                }else{
                    if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
                }

        }
        $image=$request->about_us_bg;
        $extention=$image->getClientOriginalExtension();
        $name= 'about-us-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(600,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(480,480)
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(600,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(480,480)
            ->save('public/'.$image_path);
        }


        BannerImage::where('id',$banner->id)->update([
            'about_us_bg'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return back()->with($notification);

    }


    public function subscribe(Request $request){
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'subscribe_us'=>'required',

        ];
        $customMessages = [
            'subscribe_us.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->subscribe_us){
            $oldImage=$banner->subscribe_us;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }


        }
        $image=$request->subscribe_us;
        $extention=$image->getClientOriginalExtension();
        $name= 'subscribe-us-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;

        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }



        BannerImage::where('id',$banner->id)->update([
            'subscribe_us'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return back()->with($notification);

    }

    public function doctor(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'lawyer'=>'required',

        ];
        $customMessages = [
            'lawyer.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->lawyer){
            $oldImage=$banner->lawyer;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->lawyer;
        $extention=$image->getClientOriginalExtension();
        $name= 'lawyer-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }


        BannerImage::where('id',$banner->id)->update([
            'lawyer'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return back()->with($notification);

    }

    public function lawyer(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'lawyer'=>'required',

        ];
        $customMessages = [
            'lawyer.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->lawyer){
            $oldImage=$banner->lawyer;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->lawyer;
        $extention=$image->getClientOriginalExtension();
        $name= 'lawyer-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }


        BannerImage::where('id',$banner->id)->update([
            'lawyer'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return back()->with($notification);

    }

    // Agent Banner
    public function agent(Request $request){
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
        return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
        'agent'=>'required',

        ];
        $customMessages = [
        'agent.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
            if($banner->agent){
            $oldImage=$banner->agent;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                 if(File::exists($oldImage))unlink($oldImage);
                }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
        }

        }
        $image=$request->agent;
        $extention=$image->getClientOriginalExtension();
     $name= 'agent-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
     $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
        ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }


        BannerImage::where('id',$banner->id)->update([
            'agent'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return back()->with($notification);

    }

    // Service Banner
    public function service(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'service'=>'required',

        ];
        $customMessages = [
            'service.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->service){
            $oldImage=$banner->service;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->service;
        $extention=$image->getClientOriginalExtension();
        $name= 'service-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }


        BannerImage::where('id',$banner->id)->update([
            'service'=>$image_path,
        ]);

        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);

    }

    public function department(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'department'=>'required',

        ];
        $customMessages = [
            'department.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->department){
            $oldImage=$banner->department;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->department;
        $extention=$image->getClientOriginalExtension();
        $name= 'department-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }


        BannerImage::where('id',$banner->id)->update([
            'department'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return back()->with($notification);

    }

    public function testimonial(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end
        $valid_lang=ValidationText::all();
        $rules = [
            'testimonial'=>'required',

        ];
        $customMessages = [
            'testimonial.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->testimonial){
            $oldImage=$banner->testimonial;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }


        }
        $image=$request->testimonial;
        $extention=$image->getClientOriginalExtension();
        $name= 'testimonial-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;

        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }


        BannerImage::where('id',$banner->id)->update([
            'testimonial'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return back()->with($notification);

    }

    public function faq(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'faq'=>'required',

        ];
        $customMessages = [
            'faq.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->faq){
            $oldImage=$banner->faq;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->faq;
        $extention=$image->getClientOriginalExtension();
        $name= 'faq-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }


        BannerImage::where('id',$banner->id)->update([
            'faq'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');


        return back()->with($notification);

    }

    public function contact(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'contact'=>'required',

        ];
        $customMessages = [
            'contact.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->contact){
            $oldImage=$banner->contact;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->contact;
        $extention=$image->getClientOriginalExtension();
        $name= 'contact-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'contact'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function profile(Request $request){
                        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'profile'=>'required',

        ];
        $customMessages = [
            'profile.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->profile){
            $oldImage=$banner->profile;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->profile;
        $extention=$image->getClientOriginalExtension();
        $name= 'profile-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'profile'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function login(Request $request){
                        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'login'=>'required',

        ];
        $customMessages = [
            'login.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->login){
            $oldImage=$banner->login;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->login; 
        $extention=$image->getClientOriginalExtension();
        $name= 'login-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'login'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function payment(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'payment'=>'required',

        ];
        $customMessages = [
            'payment.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->payment){
            $oldImage=$banner->payment;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->payment;
        $extention=$image->getClientOriginalExtension();
        $name= 'payment-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'payment'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function overview(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'overview'=>'required',

        ];
        $customMessages = [
            'overview.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);
        $banner=BannerImage::first();
        if($banner->overview){
            $oldImage=$banner->overview;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->overview;
        $extention=$image->getClientOriginalExtension();
        $name= 'overview-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
        Image::make($image)
        ->resize(1000,null,function ($constraint) {
            $constraint->aspectRatio();
        })
        ->save($image_path);
        }else{
        Image::make($image)
        ->resize(1000,null,function ($constraint) {
            $constraint->aspectRatio();
        })
        ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'overview'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function custom_page(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end

        $valid_lang=ValidationText::all();
        $rules = [
            'custom_page'=>'required',

        ];
        $customMessages = [
            'custom_page.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->custom_page){
            $oldImage=$banner->custom_page;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->custom_page;
        $extention=$image->getClientOriginalExtension();
        $name= 'custom_page-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'custom_page'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function blog(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'blog'=>'required',

        ];
        $customMessages = [
            'blog.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);


        $banner=BannerImage::first();
        if($banner->blog){
            $oldImage=$banner->blog;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->blog;
        $extention=$image->getClientOriginalExtension();
        $name= 'blog-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'blog'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function privacy_and_policy(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end



        $valid_lang=ValidationText::all();
        $rules = [
            'privacy_and_policy'=>'required',

        ];
        $customMessages = [
            'privacy_and_policy.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);


        $banner=BannerImage::first();
        if($banner->privacy_and_policy){
            $oldImage=$banner->privacy_and_policy;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->privacy_and_policy;
        $extention=$image->getClientOriginalExtension();
        $name= 'privacy_and_policy-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'privacy_and_policy'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function terms_and_condition(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'terms_and_condition'=>'required',

        ];
        $customMessages = [
            'terms_and_condition.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);

        $banner=BannerImage::first();
        if($banner->terms_and_condition){
            $oldImage=$banner->terms_and_condition;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->terms_and_condition;
        $extention=$image->getClientOriginalExtension();
        $name= 'terms_and_condition-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'terms_and_condition'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function admin_login(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'admin_login'=>'required',

        ];
        $customMessages = [
            'admin_login.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);


        $banner=BannerImage::first();
        if($banner->admin_login){
            $oldImage=$banner->admin_login;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->admin_login;
        $extention=$image->getClientOriginalExtension();
        $name= 'admin_login-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(464,464)
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(464,464)
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'admin_login'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function agent_login(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
            }
        // end



        $valid_lang=ValidationText::all();
        $rules = [
            'agent_login'=>'required',

        ];
        $customMessages = [
            'agent_login.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);


        $banner=BannerImage::first();
        if($banner->agent_login){
            $oldImage=$banner->agent_login;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->agent_login;
        $extention=$image->getClientOriginalExtension();
        $name= 'agent_login-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(464,464)
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(464,464)
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'agent_login'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }
    
    public function doctor_login(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end



        $valid_lang=ValidationText::all();
        $rules = [
            'doctor_login'=>'required',

        ];
        $customMessages = [
            'doctor_login.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);


        $banner=BannerImage::first();
        if($banner->doctor_login){
            $oldImage=$banner->doctor_login;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }
        $image=$request->doctor_login;
        $extention=$image->getClientOriginalExtension();
        $name= 'doctor_login-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(464,464)
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(1000,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(464,464)
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'lawyer_login'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }


    public function defaultProfile(Request $request){
                // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array('messege'=>env('NOTIFY_TEXT'),'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        // end


        $valid_lang=ValidationText::all();
        $rules = [
            'default_profile'=>'required',

        ];
        $customMessages = [
            'default_profile.required' => $valid_lang->where('lang_key','req_img')->first()->custom_lang
        ];
        $this->validate($request, $rules, $customMessages);


        $banner=BannerImage::first();
        if($banner->default_profile){
            $oldImage=$banner->default_profile;
            $root_path=request()->getHost();
            if($root_path=='127.0.0.1'){
                if(File::exists($oldImage))unlink($oldImage);
            }else{
                if(File::exists('public/'.$oldImage))unlink('public/'.$oldImage);
            }

        }

        $image=$request->default_profile;
        $extention=$image->getClientOriginalExtension();
        $name= 'default_profile-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
        $image_path='uploads/website-images/'.$name;
        $root_path=request()->getHost();
        if($root_path=='127.0.0.1'){
            Image::make($image)
            ->resize(600,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(400,400)
            ->save($image_path);
        }else{
            Image::make($image)
            ->resize(600,null,function ($constraint) {
                $constraint->aspectRatio();
            })
            ->crop(400,400)
            ->save('public/'.$image_path);
        }

        BannerImage::where('id',$banner->id)->update([
            'default_profile'=>$image_path,
        ]);
        $notify_lang=NotificationText::all();
        $notification=$notify_lang->where('lang_key','update')->first()->custom_lang;
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return back()->with($notification);
    }

    public function loginImageIndex(){
        $banner=BannerImage::first();
        $website_lang=ManageText::all();
        return view('admin.banner-image.login.index',compact('banner','website_lang'));
    }

    public function profileImageIndex(){
        $banner=BannerImage::first();
        $website_lang=ManageText::all();
        return view('admin.banner-image.profile.index',compact('banner','website_lang'));
    }


}
