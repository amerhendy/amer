@extends(Baseview('app'))
{{-- show error using sidebar layout if looged in AND on an admin page; otherwise use a blank page --}}
<?php

function between($x, $min, $max) {
  return $x >= $min && $x <= $max;
}
if(!isset($error_number)){
    $error_number=404;
}
if (between($error_number, 100,199)) {
    $heder='Successful Responses';
    $errArr=trans('AMER::errors.'.$heder);
    if(isset($errArr[$error_number])){$defmes=$errArr[$error_number];}else{$defmes=$heder;}
}elseif (between($error_number, 200,299)) {
    $heder='Information Responses';
    $errArr=trans('AMER::errors.'.$heder);
    if(isset($errArr[$error_number])){$defmes=$errArr[$error_number];}else{$defmes=$heder;}
}elseif (between($error_number, 300,399)) {
    $heder='Redirection messages';
    $errArr=trans('AMER::errors.'.$heder);
    if(isset($errArr[$error_number])){$defmes=$errArr[$error_number];}else{$defmes=$heder;}
}elseif (between($error_number, 400,499)) {
    $heder='Client error responses';
    $errArr=trans('AMER::errors.'.$heder);
    if(isset($errArr[$error_number])){$defmes=$errArr[$error_number];}else{$defmes=$heder;}
}elseif (between($error_number, 500,599)) {
    $heder='Server error responses';
    $errArr=trans('AMER::errors.'.$heder);
    if(isset($errArr[$error_number])){$defmes=$errArr[$error_number];}else{$defmes=$heder;}
}else{
    $defmes=$heder='Server error responses';
}
if(!isset($error_message)){
    $error_message=$defmes;
}
if(is_object($error_message)){
    $mess=[];
    $console=$error_message;
    if(property_exists($error_message,'message')){
        $error_message=$error_message->message;
        if(is_object($error_message)){
            foreach ($error_message->messages() as $k => $v) {
                $mess[]=$v;
            }
        }
    }
    $mess=implode('<br>',\AmerHelper::array_flatten($mess));
}else{
    $mess=$error_message;
}
?>
@php
  $title = 'Error '.$error_number;
@endphp
@if(isset($console))
    @push('after_scripts')
    <script>
        console.log({{Illuminate\Support\Js::from($console)}});
    </script>
    @endpush
@endif
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
        <br>
      {{$heder}}
      @yield('title')
    </div>
    <div class="error_description text-muted">
      <small>
        @yield('description')
        @php
        if(isset($mess)){
          print $mess.'<br>';
        }
        @endphp
        Please <a href='javascript:history.back()''>go back</a> and try again, or return to <a href='".url('')."'>our homepage</a>.
     </small>
    </div>
  </div>
</div>
@endsection
