@extends('layouts.client.layout')
@section('title')
<title>{{ $agent->seo_title }}</title>
@endsection
@section('meta')
<meta name="description" content="{{ $agent->seo_description }}">
@endsection
@section('client-content')

<!--Banner Start-->
<div class="banner-area flex" style="background-image:url({{ $banner->agent ? url($banner->agent) : '' }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner-text">
                    <h1>{{ $agent->name }} ({{ $agent->designations }})</h1>
                    <ul>
                        <li><a href="{{ url('/') }}">{{ $navigation->home }}</a></li>
                        <li><span>{{ $agent->name }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Banner End-->

<!--Team Detail Start-->
<div class="team-detail-page pt_40 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="team-detail-photo">
                    <img src="{{ url($agent->image) }}" alt="Team Photo">
                </div>
            </div>
            <div class="col-lg-8">
                <div class="team-detail-text">
                    <h4>{{ $agent->name }} </h4>
                    <span><b>{{ $agent->department->name }} ({{ $agent->designations }})</b></span>
                    <h5 class="appointment-cost">{{ $website_lang->where('lang_key','appointment_fee')->first()->custom_lang }}: {{ $currency->currency_icon }}{{ $agent->fee }}</h5>

                    {!! clean($agent->about) !!}
                    <ul>
                        @if ($agent->facebook)
                        <li><a href="{{ $agent->facebook }}"><i class="fab fa-facebook-f"></i></a></li>
                        @endif
                        @if ($agent->youtube)
                        <li><a href="{{ $agent->youtube }}"><i class="fab fa-youtube"></i></a></li>
                        @endif
                        @if ($agent->twitter)
                        <li><a href="{{ $agent->twitter }}"><i class="fab fa-twitter"></i></a></li>
                        @endif
                        @if ($agent->linkedin)
                        <li><a href="{{ $agent->linkedin }}"><i class="fab fa-linkedin"></i></a></li>
                        @endif
                        @if ($agent->linkedin)
                        <li><a href="{{ $agent->pinterest }}"><i class="fab fa-pinterest"></i></a></li>
                        @endif
                        @if ($agent->linktopage)
                        <li><a href="{{ $agent->linktopage }}"><i class="fa-brands fa-staylinked"></i></a></li>
                        @endif
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="team-exp-area bg-area pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="team-headline">
                    <h2>{{ $website_lang->where('lang_key','agent_info')->first()->custom_lang }}</h2>
                </div>
            </div>
            <div class="col-md-12">
                <!--Tab Start-->
                <div class="event-detail-tab mt_20">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a class="active" href="#working_hour" data-toggle="tab">{{ $website_lang->where('lang_key','working_hours')->first()->custom_lang }}</a>
                        </li>
                        <li>
                            <a href="#address" data-toggle="tab">{{ $website_lang->where('lang_key','address')->first()->custom_lang }}</a>
                        </li>
                        <li>
                            <a href="#education" data-toggle="tab">{{ $website_lang->where('lang_key','education')->first()->custom_lang }}</a>
                        </li>
                        <li>
                            <a href="#experience" data-toggle="tab">{{ $website_lang->where('lang_key','experience')->first()->custom_lang }}</a>
                        </li>
                        <li>
                            <a href="#qualification" data-toggle="tab">{{ $website_lang->where('lang_key','qualification')->first()->custom_lang }}</a>
                        </li>
                        <li>
                            <a href="#book_appointment" data-toggle="tab">{{ $website_lang->where('lang_key','appointment')->first()->custom_lang }}</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content event-detail-content">
                    <div id="working_hour" class="tab-pane fade show active">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="wh-table table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ $website_lang->where('lang_key','week_day')->first()->custom_lang }}</th>
                                                <th>{{ $website_lang->where('lang_key','schedule')->first()->custom_lang }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $old_day_id=0;
                                            @endphp
                                            @foreach ($agent->schedules as $schedule)
                                            @if ($old_day_id != $schedule->day_id)
                                            <tr>
                                                <td>{{ $schedule->day->custom_day }}</td>
                                                <td>
                                                    @php
                                                        $times=$agent->schedules->where('day_id',$schedule->day_id);
                                                    @endphp
                                                    @foreach ($times as $time)
                                                    <div class="sch">{{ strtoupper($time->start_time) }} - {{ strtoupper($time->end_time) }}</div>
                                                    @endforeach

                                                </td>
                                            </tr>
                                            @endif
                                            @php
                                                $old_day_id=$schedule->day_id;
                                            @endphp


                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="address" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                {!! clean($agent->address) !!}

                            </div>
                        </div>
                    </div>
                    <div id="education" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                {!! clean($agent->educations) !!}
                            </div>
                        </div>
                    </div>
                    <div id="experience" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                {!! clean($agent->experience) !!}
                            </div>
                        </div>
                    </div>
                    <div id="qualification" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                {!! clean($agent->qualifications) !!}
                            </div>
                        </div>
                    </div>
                    <div id="book_appointment" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>{{ $website_lang->where('lang_key','create_appointment')->first()->custom_lang }}</h3>

                                <div class="book-appointment">

                                    <form action="{{ url('create-appointment') }}" method="POST" >
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="" class="form-label">{{ $website_lang->where('lang_key','select_date')->first()->custom_lang }}</label>
                                                    <input type="text" name="date" class="form-control datepicker" id="datepicker-value">
                                                    <input type="hidden" name="agent_id" value="{{ $agent->id }}" id="agent_id">
                                                    <input type="hidden" value="{{ $agent->department_id }}" name="department_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-none" id="schedule-box-outer">
                                            <div class="col-md-6">
                                                <div class="mb-3" >
                                                    <label for="" class="form-label">{{ $website_lang->where('lang_key','select_schedule')->first()->custom_lang }}</label>
                                                    <select name="schedule_id" class="form-control" id="doctor-available-schedule">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 d-none" id="doctor-schedule-error">

                                            </div>
                                        </div>



                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary mb-3" id="sub" disabled>{{ $website_lang->where('lang_key','submit')->first()->custom_lang }}</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!--Tab End-->
            </div>

        </div>
    </div>
</div>
<!--Team Detail End-->


@endsection
