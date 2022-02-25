@extends('layouts.client.layout')
@section('title')
<title>{{ $navigation->payment }}</title>
@endsection
@section('client-content')


<!--Banner Start-->
<div class="banner-area flex" style="background-image:url({{ $banner->payment ?  url($banner->payment) : '' }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner-text">
                    <h1>{{ $navigation->payment }}</h1>
                    <ul>
                        <li><a href="{{ url('/') }}">{{ $navigation->home }}</a></li>
                        <li><span>{{ $navigation->payment }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Banner End-->

<!--Check Out Start-->
<div class="check-out pt_40 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="checkout-table table-responsive">

                    <h4>{{ $website_lang->where('lang_key','appointment_list')->first()->custom_lang }}</h4>

                    <table class="table">
                        <thead>
                            <tr>
                                <th><b>{{ $website_lang->where('lang_key','agent')->first()->custom_lang }}</b></th>
                                <th><b>{{ $website_lang->where('lang_key','department')->first()->custom_lang }}</b></th>
                                <th><b>{{ $website_lang->where('lang_key','location')->first()->custom_lang }}</b></th>
                                <th><b>{{ $website_lang->where('lang_key','date')->first()->custom_lang }}</b></th>
                                <th><b>{{ $website_lang->where('lang_key','time')->first()->custom_lang }}</b></th>
                                <th><b>{{ $website_lang->where('lang_key','appointment_fee')->first()->custom_lang }} ({{ $currency->currency_name }})</b></th>
                                <th><b>{{ $website_lang->where('lang_key','action')->first()->custom_lang }}</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $i => $item)
                            <tr>
                                <td>{{ ucfirst($item->name) }}</td>
                                <td>{{ $item->options->department }}</td>
                                <td>{{ $item->options->location }}</td>
                                <td>{{ $item->options->date }}</td>
                                <td>{{ strtoupper($item->options->time) }}</td>
                                <td>{{ $currency->currency_icon }}{{ $item->price }}</td>
                                <td><a href="{{ url('remove-appointment/'.$item->rowId) }}" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></a></td>
                            </tr>
                            @endforeach


                            <tr>
                                <td class="text-right" colspan="5"><b>Total</b></td>
                                <td class="" colspan="2"><b>{{ $currency->currency_icon }}{{ Cart::pricetotal() }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if ($appointments->count() !=0)
        <div class="row">
            <div class="col-12">
                <div class="payment-select">
                    <h4>{{ $website_lang->where('lang_key','pay_now')->first()->custom_lang }}</h4>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">

                        @if ($paymentSetting->stripe_status==1)
                            <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="stripe-tab" data-toggle="tab" href="#stripe" role="tab" aria-controls="stripe" aria-selected="true">{{ $website_lang->where('lang_key','stripe')->first()->custom_lang }}</a>
                            </li>
                        @endif

                        @if ($paymentSetting->paypal_status==1)
                            <li class="nav-item" role="presentation">
                            <a class="nav-link" id="paypal-tab" data-toggle="tab" href="#paypal" role="tab" aria-controls="paypal" aria-selected="false">{{ $website_lang->where('lang_key','paypal')->first()->custom_lang }}</a>
                            </li>
                        @endif

                        @if ($razorpay->razorpay_status==1)
                            <li class="nav-item" role="presentation">
                            <a class="nav-link" id="razorpay-tab" data-toggle="tab" href="#razorpay" role="tab" aria-controls="paypal" aria-selected="false">{{ $website_lang->where('lang_key','razorpay')->first()->custom_lang }}</a>
                            </li>
                        @endif


                        @if ($paymentSetting->bank_status==1)
                            <li class="nav-item" role="presentation">
                            <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab" aria-controls="bank" aria-selected="false">{{ $website_lang->where('lang_key','bank_transfer')->first()->custom_lang }}</a>
                            </li>
                        @endif

                      </ul>
                      <div class="tab-content" id="myTabContent">
                        @if ($paymentSetting->stripe_status==1)
                            <div class="tab-pane fade show active mt-5" id="stripe" role="tabpanel" aria-labelledby="stripe-tab">
                                <div class="payment-select-group">
                                    @if (Session::has('success'))
                                        <div class="alert alert-success text-center">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                            <p>{{ Session::get('success') }}</p>
                                        </div>
                                    @endif
                                    <form role="form" action="{{ route('client.stripe.payment') }}" method="post" class="require-validation"
                                    data-cc-on-file="false"
                                    data-stripe-publishable-key="{{ $stripe->stripe_key }}"
                                    id="payment-form">
                                    @csrf

                                    <div class='form-row row mt_30'>
                                        <div class='col-sm-6 col-12 form-group required'>
                                            <label class='control-label'>{{ $website_lang->where('lang_key','card_number')->first()->custom_lang }}</label> <input
                                                autocomplete='off' class='form-control card-number' size='20'
                                                type='text' name="card_digit">
                                        </div>


                                        <div class='col-sm-6 col-12 form-group cvc required'>
                                            <label class='control-label'>{{ $website_lang->where('lang_key','cvc')->first()->custom_lang }}</label> <input autocomplete='off'
                                                class='form-control card-cvc' size='4'
                                                type='text'>
                                        </div>

                                        <div class='col-sm-6 col-12 form-group expiration required'>
                                            <label class='control-label'>{{ $website_lang->where('lang_key','exp_month')->first()->custom_lang }}</label> <input
                                                class='form-control card-expiry-month' size='2'
                                                type='text'>
                                        </div>

                                        <div class='col-sm-6 col-12 form-group expiration required'>
                                            <label class='control-label'>{{ $website_lang->where('lang_key','exp_year')->first()->custom_lang }}</label> <input
                                                class='form-control card-expiry-year'  size='4'
                                                type='text'>
                                        </div>
                                    </div>
                                    <div class='form-row row'>
                                        <div class='col-md-12 error form-group hide d-none'>
                                            <div class='alert-danger alert'>{{ $website_lang->where('lang_key','card_error')->first()->custom_lang }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="payment-order-button col-3">
                                        <button type="submit">{{ $website_lang->where('lang_key','pay_with_stripe')->first()->custom_lang }}</button>
                                    </div>
                                </div>
                            </form>
                            </div>
                        @endif

                        @if ($paymentSetting->paypal_status==1)
                        <div class="tab-pane fade mt-5" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
                            <form action="{{ url('client/store-paypal') }}" method="POST">
                                @csrf
                            <div class="row">
                                <div class="payment-order-button col-3">
                                    <button type="submit">{{ $website_lang->where('lang_key','pay_with_paypal')->first()->custom_lang }}</button>
                                </div>
                            </div>
                        </form>
                        </div>
                        @endif

                        @if ($razorpay->razorpay_status==1)
                        <div class="tab-pane fade mt-5" id="razorpay" role="tabpanel" aria-labelledby="razorpay-tab">
                            <form action="{{ route('client.razorpay-payment') }}" method="POST" >
                                @csrf

                                @php
                                    $inr_amount=Cart::pricetotal() * $razorpay->currency_rate;
                                @endphp

                                <script src="https://checkout.razorpay.com/v1/checkout.js"
                                        data-key="{{ $razorpay->razorpay_key }}"
                                        data-amount= "{{ $inr_amount * 100 }}"
                                        data-buttontext="{{ $website_lang->where('lang_key','pay')->first()->custom_lang }} {{ $inr_amount }} {{ $website_lang->where('lang_key','inr')->first()->custom_lang }}"
                                        data-name="{{ $razorpay->name }}"
                                        data-description="{{ $razorpay->description }}"
                                        data-image="{{ asset($razorpay->image) }}"
                                        data-prefill.name=""
                                        data-prefill.email=""
                                        data-theme.color="{{ $razorpay->theme_color }}">
                                </script>
                            </form>
                        </div>
                        @endif


                        @if ($paymentSetting->bank_status==1)
                        <div class="tab-pane fade mt-5" id="bank" role="tabpanel" aria-labelledby="bank-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ route('client.bank.payment') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="description">{{ $website_lang->where('lang_key','tran_info')->first()->custom_lang }}</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="form-control" required></textarea>
                                        </div>

                                        <div class="payment-order-button">
                                            <button type="submit">{{ $website_lang->where('lang_key','payment')->first()->custom_lang }}</button>
                                        </div>

                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <div class="bank contact-info-item bg2 mb_30 mt_30">
                                        <div class="contact-info">
                                            <span>
                                                {{ $website_lang->where('lang_key','bank_acc_info')->first()->custom_lang }}:
                                            </span>
                                            <div class="contact-text">
                                                <p class="card-text">
                                                    {!! clean(nl2br(e($stripe->bank_account))) !!}</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        @endif

                      </div>

                </div>

            </div>
        </div>
        @endif
    </div>
</div>
<!--Check Out End-->




@endsection
