@extends('layouts.agent.layout')
@section('title')
<title>View Appointment</title>
@endsection

@section('agent-content')
<div class="card shadow mb-4">
    <div class="card-body" id="search-particular-appointment">
        <div class="prescription">
            <div class="top">
                <div class="row">
                    <div class="col-md-6">
                        <div class="logo"><img src="{{ url($setting->logo) }}" alt=""></div>
                        @if ( $agent->prescription_address)
                            <div class="address">
                                <i class="fas fa-map-marker-alt"></i> {{ $agent->prescription_address }}
                            </div>
                        @endif

                        @if ($agent->prescription_phone)
                        <div class="phone">
                            <i class="fas fa-phone"></i> {{ $agent->prescription_phone }}
                        </div>
                        @endif

                        @if ($agent->prescription_email)
                        <div class="email">
                            <i class="far fa-envelope"></i>  {{ $agent->prescription_email }}
                        </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="right">
                            <h2>{{ $appointment->agent->name }}</h2>
                            <p>
                                {{ $appointment->agent->designations }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="patient-info table-{{ $appointment->stage }} shadow">
                <div class="row px-2">
                    <div class="col-md-6">
                        @isset($appointment->user->name)
                        Name: {{ $appointment->user->name }}
                        @else
                        Name: {{ $appointment->guestname }}
                        @endisset
                    </div>
                    <div class="col-md-3">
                        @isset($appointment->user->email)
                        Email: {{ $appointment->user->email }}
                        @else
                        Email: {{ $appointment->email }}
                        @endisset
                    </div>
                    <div class="col-md-3 text-right">
                        Date: {{ date('m-d-Y',strtotime($appointment->date)) }}
                    </div>
                </div>
            </div>


            <div class="mt-3">
                <div>
                    {{-- @csrf --}}
                    
                    <div class="text-center mb-4">
                        <img class="mb-4" src="{{ asset('/storage/main_imgs/400x300.png') }}" alt="" width="222" height="222">
                        <h1 class="h3 mb-3 font-weight-normal">
                            <u>Scheduled Appointment</u>
                            @if ($appointment->stage=='success')
                                <span class="badge badge-success">Approved</span>
                            @endif
                            @if ($appointment->stage=='warning')
                                <span class="badge badge-warning">Pending</span>
                            @endif
                            @if ($appointment->stage=='danger')
                            <span class="badge badge-danger">Cancelled</span>
                            @endif
                            @if ($appointment->stage=='dark')
                            <span class="badge badge-dark">Compleated</span>
                            @endif
                            
                        </h1>
          Customer Name:<h4>
                            @isset($appointment->user->name)
                            {{ $appointment->user->name }}
                            @else
                            {{ $appointment->guestname }}
                            @endisset
                        </h4>
          Customer Email:<h4>
                            @isset($appointment->user->email)
                            {{ $appointment->user->email }} Years
                            @else
                            {{ $appointment->email }}
                            @endisset
                        </h4>
                        <h4>
                            @isset($appointment->user->phone)
                        Phone: {{ $appointment->user->phone }}
                            @else
                        Phone: {{ $appointment->phone }}
                            @endisset
                        </h4>
                        <h4>
                            <small>
                                <b>Day:</b>
                             </small> 
                             <u>{{ date('m-d-Y',strtotime($appointment->date)) }}</u> 
                             <small>
                                 <b>Time:</b>
                             </small>
                             <u>{{ $appointment->time }}</u>
                         </h4>
                         <br>                        
                         <h4>Notes:<br> 
                             @isset($appointment->note)
                             <b>{{ $appointment->note }}</b>
                             @else
                                 <small><i>No Notes were entered by this customer...</i></small>
                             @endisset
                         </h4><br><br>
                        <p>Additional informatin can be added to this page. Fees, related work, etc...<br>Orders can only be edited, <code>Appointments CANNOT be edited from here!!! </code></p>
                    </div>

                          
                    
                     <div class="row">
                        <div class="col-md-8">
                            <div id="addRow">
                                <div class="row">
                                    
                                </div>

                            </div>
                        </div>
                    </div>
                    <a href="/agent/all-appointment" class="btn btn-dark">Return</a>
                </div>
            </div>

            <p class="mt-5 mb-3 text-muted text-center">WesPier Pro. &copy; 2019-2022</p>

        </div>
    </div>
</div>

@endsection
