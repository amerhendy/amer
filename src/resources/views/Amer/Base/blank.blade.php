<?php
//dd(convert_uuencode(json_encode([convert_uuencode(env("API_CLIENT_ID") ?? ""),convert_uuencode(env("API_CLIENT_SECRET") ?? "")])));
$website=\Str::finish(config('app.url'),'/');
$apilinks=\Str::finish($website.'api/'.config("Amer.Amer.api_version")??"",'/');
?>
<!-- app -->
<!DOCTYPE html>
<html lang="{{config('Amer.Amer.lang') ?? 'ar-eg'}}" dir="{{config('Amer.Amer.html_direction') ?? 'rtl'}} " prefix="{{config('Amer.Amer.co_name') ?? 'HCWW'}}" data-bs-theme="auto">
<head>
    @stack('meta')
    <title>{{$page_title ?? config('Amer.Amer.co_name') ?? 'Amer'}} :: {{config('Amer.Amer.co_name') ?? 'Amer'}} </title>
    <base href="{{url('')}}">
    <meta charset="{{config('Amer.Amer.ENCODE') ?? 'UTF-8'}}">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset={{config('Amer.Amer.ENCODE') ?? 'UTF-8'}}">
    <meta name="language" content="Arabic">
    <meta name="revisit-after" content="7 days">
    <meta name="author" content="amer hendy">
    <meta name="generator" content="amer hendy"/>
    <meta name="referrer" content="origin"/>
    <meta name="referrer" content="origin-when-crossorigin"/>
    <meta name="referrer" content="origin-when-cross-origin"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset ('images/logo.png') ?? ''}}" rel="icon">
    <link href="{{asset ('images/logo.png') ?? ''}}" rel="apple-touch-icon">
    <link rel="alternate" type="application/rss+xml" title="Simplest Web" href="{{url('rss') ?? ''}}" />
    @section('header-meta')
    @show
    @yield('before_styles')
    @stack('before_styles')
    @loadStyleOnce('css/app.css')
    <style>
        :root, [data-bs-theme=light] {
            --bs-link-color-rgb: 250, 250, 250;
        }
        [data-bs-theme=light] {
            --fa--map--maker:#000;
        }
        @font-face {
            font-family: AmerHendyAli;
            src: url('{{asset("fonts/c.ttf")}}');
        }
        body{
            font-family: 'AmerHendyAli', sans-serif,'Big Shoulders Display', cursive;
            direction: rtl;
        }
        html,body,div,li,nav,ul,a,.breadcrumb,header,.section ,.pace{
                font-family: 'AmerHendyAli'!important;
                direction: rtl;
        }
    </style>
    @yield('after_styles')
    @stack('after_styles')
</head>
@stack('beforebody')
<body>
@stack('afterbody')
<div id='loader' class="container-fluid justify-content-center full-width-div">
    <div class="my-auto">
        <div class="spinner-grow m-5" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>
<main class="section fixed container-fluid">
    <div class="row">
        <div class="col-sm-12">
            @include(mainview('main.Alert'))
            <div class="container-fluid" id="mainContent">
            @yield('content')
            </div>
        </div>
    </div>
</main>
</body>
@yield('before_scripts')
@stack('before_scripts')
<script type="application/javascript">
    if(!window.Amer){
        window.Amer={};
      }
      if(!window.Amer.forms){
        window.Amer.forms={};
      }
      window.Amer.Language="{{app()->getLocale()}}";
      window.Amer.LangFallback= '{{ Lang::getFallback() }}';
      window.Amer.dir="{{config('Amer.Amer.html_direction') ?? 'rtl'}}";
      window.Amer.bootstrap="bootstrap-5";
    var websitelink="{{$website}}";
    var api='{{\Str::finish($website."api/".config("Amer.Amer.api_version")??"","/")}}';
    const clientInfo=`{{base64_encode(json_encode([base64_encode(config("Amer.Amer.API_CLIENT_ID") ?? ""),base64_encode(config("Amer.Amer.API_CLIENT_SECRET") ?? "")]))}}`;
    var jstrans=[];
    jstrans['actions']={{ Illuminate\Support\Js::from(trans('AMER::actions')) }}
    jstrans['error']="{{trans('AMER::errors.ajax_error_title')}}";
</script>
@yield('after_scripts')
@stack('after_scripts')
</html>
