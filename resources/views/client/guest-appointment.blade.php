@extends('layouts.client.layout')
@section('title')
<title>Privacy Policy</title>
@endsection
@section('client-content')

<!--Banner Start-->
<div class="banner-area flex" style="background-image:url">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner-text">
                    <h1></h1>
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><span></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Banner End-->


<div class="about-style1 pt_50 pb_50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Your Appointment has been submited successfully.</h1><br><h4>One of our Agents will contact you shortly to verify your appointment,<br> Thank you.</h4>

            </div>
        </div>
    </div>
</div>

@endsection
