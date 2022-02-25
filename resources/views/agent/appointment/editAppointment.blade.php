@extends('layouts.agent.layout')
@section('title')
<title>Edit Appointment</title>
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

            <div class="patient-info">
                <div class="row">
                    <div class="col-md-6">
                        @isset($appointment->user->name)
                        {{ $appointment->user->name }}
                        @else
                        {{ $appointment->guestname }}
                        @endisset
                    </div>
                    <div class="col-md-3">
                        @isset($appointment->user->email)
                        {{ $appointment->user->email }} Years
                        @else
                        {{ $appointment->email }}
                        @endisset
                    </div>
                    <div class="col-md-3 text-right">
                        {{ date('m-d-Y',strtotime($appointment->date)) }}
                    </div>
                </div>
            </div>


            <div class="mt-3">
                <form action="/agent/all-appointment" enctype="multipart/form-data" method="get">
                    {{-- @csrf --}}
                    
                    <div class="text-center mb-4">
                        <img class="mb-4" src="{{ asset('/storage/main_imgs/notary-seal.png') }}" alt="" width="111" height="111">
                        <h1 class="h3 mb-3 font-weight-normal">Appointment Re-Schedule</h1>
                        <p>In this section you can change the day by selecting a different date and available hours for that day.<br>Orders can only be edited, <code>They CANNOT be deleted!!! </code></p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-4">
                            <div class="form-label-group">
                                <label for="date">Select Date</label>
                                <input type="date" id="inputDate" class="form-control" placeholder="" value="{{ $appointment->date }}" required autofocus>
                            </div>
                        
                            <div class="mt-3 form-label-group">
                                <label for="">Schedule</label>
                                <input type="text" id="inputPassword" class="form-control" value="{{ $appointment->time }}" placeholder="Available Schedule" required>
                            </div>
                        </div>
                    </div>
                    <div class="text mb-3">
                        <p>
                           Remember me
                        </p>
                    </div>            
                    
                     <div class="row">
                        <div class="col-md-8">
                            <div id="addRow">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="">Documents</label>
                                            <input type="file" name="documents[]" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 btn-row">
                                        <button type="button" id="addagentFile" class="btn btn-success" ><i class="fas fa-plus" aria-hidden="true"></i></button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit">Update</button>
                </form>
            </div>

            <p class="mt-5 mb-3 text-muted text-center">WesPier Pro. &copy; 2019-2022</p>

        </div>
    </div>
</div>

@endsection
