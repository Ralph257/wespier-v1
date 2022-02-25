@extends('layouts.client.layout')
@section('title')
<title>{{ $title_meta->agent_title }}</title>
@endsection
@section('meta')
<meta name="description" content="{{ $title_meta->agent_meta_description }}">
@endsection
@section('client-content')

<!--Banner Start-->
<div class="banner-area flex" style="background-image:url({{ $banner->agent ? url($banner->agent) : '' }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner-text">
                    <h1>{{ $navigation->agent }}</h1>
                    <ul>
                        <li><a href="{{ url('/') }}">{{ $navigation->home }}</a></li>
                        <li><span>{{ $navigation->agent }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Banner End-->


<div class="doctor-search">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="s-container">
                <form action="{{ url('search-agent') }}">

                    <div class="s-box">
                        <select name="location" class="form-control select2">
                            <option value="">{{ $website_lang->where('lang_key','select_location')->first()->custom_lang }}</option>
                            @foreach ($locations as $location)
                            <option {{ @$location_id==$location->id?'selected':'' }} value="{{ $location->id }}">{{ ucwords($location->location) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="s-box">
                        <select name="department" class="form-control select2">
                            <option value="">{{ $website_lang->where('lang_key','select_department')->first()->custom_lang }}</option>
                            @foreach ($departments as $department)
                            <option {{ @$department_id==$department->id?'selected':'' }} value="{{ $department->id }}">{{ ucwords($department->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="s-box">
                        <select name="agent" class="form-control select2">
                            <option value="">{{ $website_lang->where('lang_key','select_agent')->first()->custom_lang }}</option>
                            @foreach ($agentsForSearch as $agent)
                            <option {{ @$agent_id==$agent->id?'selected':'' }} value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="s-button">
                        <button type="submit">{{ $website_lang->where('lang_key','search')->first()->custom_lang }}</button>
                    </div>
                </form>
                </div>

            </div>
        </div>
    </div>
</div>




<!--Service Start-->
<div class="team-page pt_40 pb_70">
    <div class="container">
        <div class="row">

            @if ($agents->count()!=0)
            @foreach ($agents as $agent)
            <div class="col-lg-3 col-md-4 col-6 mt_30">
                <div class="team-item">
                    <div class="team-photo">
                        <img src="{{ url($agent->image) }}" alt="Team Photo">
                    </div>
                    <div class="team-text">
                        <a href="{{ url('agent-details/'.$agent->slug) }}">{{ $agent->name }}</a>
                        <p>{{ $agent->department->name }}</p>
                        <p><span><i class="fas fa-graduation-cap"></i> {{ $agent->designations }}</span></p>
                        <p><span><b><i class="fas fa-street-view"></i> {{ ucfirst($agent->location->location) }}</b></span></p>
                    </div>
                    <div class="team-social">
                        <ul>
                            @if ($agent->facebook)
                            <li><a href="{{ $agent->facebook }}"><i class="fab fa-facebook-f"></i></a></li>
                            @endif
                            @if ($agent->twitter)
                            <li><a href="{{ $agent->twitter }}"><i class="fab fa-twitter"></i></a></li>
                            @endif
                            @if ($agent->linkedin)
                            <li><a href="{{ $agent->linkedin }}"><i class="fab fa-linkedin"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <h3 class="text-danger text-center">{{ $website_lang->where('lang_key','lawyer_not_found')->first()->custom_lang }}</h3>
            @endif


        </div>
        {{ @$agents->links('client.paginator') }}
    </div>
</div>
<!--Service End-->






@endsection
