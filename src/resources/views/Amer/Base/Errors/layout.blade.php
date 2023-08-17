@extends(Baseview('app'))
{{-- show error using sidebar layout if looged in AND on an admin page; otherwise use a blank page --}}

@php
  $title = 'Error '.$error_number;
@endphp

@section('after_styles')
  <style>
    .error_number {
      font-size: 156px;
      font-weight: 600;
      line-height: 100px;
    }
    .error_number small {
      font-size: 56px;
      font-weight: 700;
    }

    .error_number hr {
      margin-top: 60px;
      margin-bottom: 0;
      width: 50px;
    }

    .error_title {
      margin-top: 40px;
      font-size: 36px;
      font-weight: 400;
    }

    .error_description {
      font-size: 24px;
      font-weight: 400;
    }
  </style>
@endsection
@php
    $default_error_message = "Please <a href='javascript:history.back()''>go back</a> and try again, or return to <a href='".url('')."'>our homepage</a>.";
  @endphp
@section('content')
<div class="row">
  <div class="col-md-12 text-center">
    <div class="error_number">
      
      <small>ERROR</small><br>
      {{ $error_number }}
      <hr>
    </div>
    <div class="error_title text-muted">
      @yield('title')
    </div>
    <div class="error_description text-muted">
      <small>
        @yield('description')
        @php
        if(isset($error_message)){
          print $error_message.'<br>';
        }
        @endphp
        Please <a href='javascript:history.back()''>go back</a> and try again, or return to <a href='".url('')."'>our homepage</a>.
     </small>
    </div>
  </div>
</div>
@endsection