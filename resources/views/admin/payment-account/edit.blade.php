@extends('layouts.admin.layout')
@section('title')
<title>{{ $website_lang->where('lang_key','payment_account')->first()->custom_lang }}</title>
@endsection
@section('admin-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $website_lang->where('lang_key','payment_account')->first()->custom_lang }}</h6>
                </div>
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                          <a class="nav-link active" id="paypal-tab" data-toggle="tab" href="#paypal-account" role="tab" aria-controls="paypal-account" aria-selected="true">{{ $website_lang->where('lang_key','paypal')->first()->custom_lang }}</a>

                          <a class="nav-link" id="stripe-tab" data-toggle="tab" href="#stripe-account" role="tab" aria-controls="nav-profile" aria-selected="false">{{ $website_lang->where('lang_key','stripe')->first()->custom_lang }}</a>

                          <a class="nav-link" id="razorpay-tab" data-toggle="tab" href="#razorpay-account" role="tab" aria-controls="razorpay-account" aria-selected="false">{{ $website_lang->where('lang_key','razorpay')->first()->custom_lang }}</a>

                          <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank-account" role="tab" aria-controls="razorpay-account" aria-selected="false">{{ $website_lang->where('lang_key','bank')->first()->custom_lang }}</a>

                        </div>
                      </nav>
                      <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="paypal-account" role="tabpanel" aria-labelledby="paypal-tab">
                            <div class="mt-4">
                                <form action="{{ route('admin.payment-account.update',$paymentAccount->id) }}" method="POST">
                                    @csrf
                                    @method('patch')

                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','paypal_mode')->first()->custom_lang }}</label>
                                        <select name="account_mode" id="" class="form-control">
                                            <option {{ $paymentAccount->account_mode=='live' ? 'selected':'' }} value="live">{{ $website_lang->where('lang_key','live')->first()->custom_lang }}</option>
                                            <option {{ $paymentAccount->account_mode=='sandbox' ? 'selected':'' }} value="sandbox">{{ $website_lang->where('lang_key','sandbox')->first()->custom_lang }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="paypal_client_id">{{ $website_lang->where('lang_key','paypal_client_id')->first()->custom_lang }}</label>
                                        <textarea name="paypal_client_id" id="paypal_client_id" cols="30" rows="2" class="form-control">{{ $paymentAccount->paypal_client_id }}</textarea>

                                    </div>
                                    <div class="form-group">
                                        <label for="paypal_secret">{{ $website_lang->where('lang_key','paypal_secret_key')->first()->custom_lang }}</label>
                                        <textarea name="paypal_secret" id="paypal_secret" cols="30" rows="2" class="form-control" >{{ $paymentAccount->paypal_secret }}</textarea>

                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','status')->first()->custom_lang }}</label>
                                        <select name="paypal_status" id="" class="form-control">
                                            <option {{ $paymentAccount->paypal_status==1 ? 'selected':'' }} value="1">{{ $website_lang->where('lang_key','active')->first()->custom_lang }}</option>
                                            <option {{ $paymentAccount->paypal_status==0 ? 'selected':'' }} value="0">{{ $website_lang->where('lang_key','inactive')->first()->custom_lang }}</option>
                                        </select>
                                    </div>


                                    <button type="submit" class="btn btn-success">{{ $website_lang->where('lang_key','update')->first()->custom_lang }}</button>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="stripe-account" role="tabpanel" aria-labelledby="stripe-tab">
                            <div class="mt-3">
                                <form action="{{ route('admin.stripe-update',$paymentAccount->id) }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="stripe_key">{{ $website_lang->where('lang_key','stripe_key')->first()->custom_lang }}</label>
                                        <textarea name="stripe_key" id="stripe_key" cols="30" rows="2" class="form-control">{{ $paymentAccount->stripe_key }}</textarea>

                                    </div>
                                    <div class="form-group">
                                        <label for="stripe_secret">{{ $website_lang->where('lang_key','stripe_secret')->first()->custom_lang }}</label>
                                        <textarea name="stripe_secret" id="stripe_secret" cols="30" rows="2" class="form-control">{{ $paymentAccount->stripe_secret }}</textarea>

                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','status')->first()->custom_lang }}</label>
                                        <select name="stripe_status" id="" class="form-control">
                                            <option {{ $paymentAccount->stripe_status==1 ? 'selected':'' }} value="1">{{ $website_lang->where('lang_key','active')->first()->custom_lang }}</option>
                                            <option {{ $paymentAccount->stripe_status==0 ? 'selected':'' }} value="0">{{ $website_lang->where('lang_key','inactive')->first()->custom_lang }}</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-success">{{ $website_lang->where('lang_key','update')->first()->custom_lang }}</button>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="razorpay-account" role="tabpanel" aria-labelledby="razorpay-tab">
                            <div class="mt-3">
                                <form action="{{ route('admin.razorpay-update',$razorpay->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','razorpay_key')->first()->custom_lang }}</label>
                                        <input type="text" class="form-control" name="razorpay_key" value="{{ $razorpay->razorpay_key }}">

                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','razorpay_secret_key')->first()->custom_lang }}</label>
                                        <input type="text" class="form-control" name="razorpay_secret" value="{{ $razorpay->secret_key }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','name')->first()->custom_lang }}</label>
                                        <input type="text" class="form-control" name="name" value="{{ $razorpay->name }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','description')->first()->custom_lang }}</label>
                                        <input type="text" class="form-control" name="description" value="{{ $razorpay->description }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">{{ $website_lang->where('lang_key','status')->first()->custom_lang }}</label>
                                                <select name="razorpay_status" id="" class="form-control">
                                                    <option {{ $razorpay->razorpay_status==1 ? 'selected':'' }} value="1">{{ $website_lang->where('lang_key','active')->first()->custom_lang }}</option>
                                                    <option {{ $razorpay->razorpay_status==0 ? 'selected':'' }} value="0">{{ $website_lang->where('lang_key','inactive')->first()->custom_lang }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="">{{ $website_lang->where('lang_key','active_currency')->first()->custom_lang }}</label>
                                                <input type="text" value="{{ $razorpay->currency_rate }}" class="form-control" name="currency_rate">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','exist_image')->first()->custom_lang }}</label>
                                        <div>
                                            <img width="200px" src="{{ asset($razorpay->image) }}" alt="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','image')->first()->custom_lang }}</label>
                                        <input type="file" class="form-control-file" name="image">
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','theme_color')->first()->custom_lang }}</label>
                                        <br>
                                        <input type="color" name="theme_color" value="{{ $razorpay->theme_color }}">
                                    </div>

                                    <button type="submit" class="btn btn-success">{{ $website_lang->where('lang_key','update')->first()->custom_lang }}</button>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="bank-account" role="tabpanel" aria-labelledby="bank-tab">
                            <div class="mt-3">
                                <form action="{{ route('admin.bank-update',$paymentAccount->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="bank_account">{{ $website_lang->where('lang_key','bank_account')->first()->custom_lang }}</label>
                                        <textarea name="bank_account" id="bank_account" cols="30" rows="5" class="form-control" >{{ $paymentAccount->bank_account }}</textarea>

                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ $website_lang->where('lang_key','status')->first()->custom_lang }}</label>
                                        <select name="bank_status" id="" class="form-control">
                                            <option {{ $paymentAccount->bank_status==1 ? 'selected':'' }} value="1">{{ $website_lang->where('lang_key','active')->first()->custom_lang }}</option>
                                            <option {{ $paymentAccount->bank_status==0 ? 'selected':'' }} value="0">{{ $website_lang->where('lang_key','inactive')->first()->custom_lang }}</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-success">{{ $website_lang->where('lang_key','update')->first()->custom_lang }}</button>
                                </form>
                            </div>
                        </div>

                      </div>
                </div>
            </div>
        </div>
    </div>

@endsection
