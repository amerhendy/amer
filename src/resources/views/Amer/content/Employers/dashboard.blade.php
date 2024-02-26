@extends(backpack_view('layouts.top_left'))
@push('header')

@endpush

@push('after_styles')
    <style>
        .requireinput{
            border-color: #0B90C4;
        }
        .select2-results__group{
        background-color:gray;
        }
        .has-error{
            border-color: rgb(185, 74, 72) !important;
        }
    </style>
    <link href="{{asset('packages/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.css')}}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
@if(auth::guard('Employers')->user())
        <div class="row">
            <div class="col-sm-1">الاسم</div>
            <div class="col-sm-3">{{$user->fullname}}</div>
            <div class="col-sm-1">الرقم الوظيفى</div>
            <div class="col-sm-3">{{$user->uid}}</div>
            <div class="col-sm-1">الرقم القومى</div>
            <div class="col-sm-3">{{$user->nid}}</div>
    </div>
    <div class="row">
            <div class="col-sm-1">الوظيفة</div>
            <div class="col-sm-11">{{$user['JobName'][0]->text ?? ''}}</div>
            <div class="col-sm-1">موقف العمل</div>
            <div class="col-sm-3"></div>
    </div>
    <div class="row">
        @php
            //dd($user['JobName'][0]->id);
        @endphp
        <div class="col-sm-1">
                <a href="{{route('Mosama_print.index')}}" target="_blank"  onclick="event.preventDefault();document.getElementById('Mosama_print-form').submit();">
                الوصف الوظيفى</a>
                <form id="Mosama_print-form" action="{{ route('Mosama_print.index') }}" method="POST" style="display: none;"  target="_blank">
                                        @csrf
                                        <input type="text" name="jobnameselect[]" value="{{$user['JobName'][0]->id}}">
                                    </form>
                                </div>
                                @if(count($Regulations))
        <div class="col-sm-3">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item me-3 me-lg-0 dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="Regulations" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bank"></i>لوائح العمل
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="Regulations">
                        @foreach($Regulations as $Regulation)
                        <li>
                            <a class="dropdown-item" href="{{route('Regulations')}}"  target="_blank"  onclick="event.preventDefault();document.getElementById('Regulations-{{$Regulation['id']}}-form').submit();">{{$Regulation['text']}}<i class="fa fa-group"></i></a>
                            <form id="Regulations-{{$Regulation['id']}}-form" action="{{ route('Regulations') }}" method="POST" style="display: none;"  target="_blank">
                                        @csrf
                                        <input type="text" name="Regulationid" value="{{$Regulation['id']}}">
                                    </form>
                        </li>
                        @endforeach
                    </ul>
                    
                </li>
            </ul>
        </div>
        @endif                        
        </div>
        
        
@endif
@endsection