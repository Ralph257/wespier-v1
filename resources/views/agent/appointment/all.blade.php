@extends('layouts.agent.layout')
@section('title')
<title>{{ $website_lang->where('lang_key','appointment_history')->first()->custom_lang }}</title>
@endsection
@section('agent-content')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ $website_lang->where('lang_key','appointment_history')->first()->custom_lang }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">{{ $website_lang->where('lang_key','serial')->first()->custom_lang }}</th>
                            <th width="15%">{{ $website_lang->where('lang_key','name')->first()->custom_lang }}</th>
                            <th width="15%">{{ $website_lang->where('lang_key','email')->first()->custom_lang }}</th>
                            <th width="15%">{{ $website_lang->where('lang_key','phone')->first()->custom_lang }}</th>
                            <th width="15%">{{ $website_lang->where('lang_key','date')->first()->custom_lang }}</th>
                            <th width="25%">{{ $website_lang->where('lang_key','time')->first()->custom_lang }}</th>
                            <th width="10%">{{ $website_lang->where('lang_key','action')->first()->custom_lang }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $index => $item)
                        <tr>
                            <td>{{ ++$index }}</td>

                            @isset($item->user->name)
                            <td class="table-{{ $item->stage }}">
                                <a href="{{ route('agent.showAppointment',$item->id) }}">
                                    {{ $item->user->name }}
                                </a>
                            </td>
                            @else
                            <td class="table-{{ $item->stage }}">
                                <a href="{{ route('agent.showAppointment',$item->id) }}">
                                    {{ $item->guestname }}
                                </a>
                            </td>
                            @endisset
                            
                            @isset($item->user->email)
                            <td>
                                {{ $item->user->email }}
                            </td>
                            @else
                            <td>
                                {{ $item->email }}
                            </td>
                            @endisset
                            @isset($item->user->phone)
                            <td>
                                {{ $item->user->phone }}
                            </td>
                            @else
                            <td>
                                {{ $item->phone }}
                            </td>
                            @endisset
                            <td>{{ date('m-d-Y',strtotime($item->date)) }}</td>
                            <td>{{ strtoupper(@$item->schedule->start_time).'-'.strtoupper(@$item->schedule->end_time) }}</td>
                            <td>
                                @if ($item->already_treated==1)
                                <a href="{{ route('agent.already-meet',$item->id) }}" class="btn btn-success btn-sm"> <i class="fas fa-eye"></i></a>
                                @endif

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>




@endsection
