<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ManageText;
class AgentScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:agent');
    }
    public function index(){
        $agent=Auth::guard('agent')->user();
        $website_lang=ManageText::all();
        return view('agent.schedule.index',compact('agent','website_lang'));
    }
}
