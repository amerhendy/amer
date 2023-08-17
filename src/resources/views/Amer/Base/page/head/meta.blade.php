<!-- inc.head.headmeta -->
    <title>{{$page_title ?? config('amer.co_name') ?? 'HCWW'}} :: {{config('amer.co_name') ?? 'HCWW'}} </title>
    <base href="{{url('')}}">
    <meta name="theme-color" content    ="{{config('amer.html.theme-color') ?? 'white'}}">
    <meta name="description" content="{{config('amer.html.description') ?? 'sinai water'}}" />
    <meta property="og:title" content="{{config('amer.co_name') ?? 'HCWW'}}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{url('')}}" />
    <meta property="og:image" content="{{asset(config('amer.co_logo'))}}" />
    <meta property="og:image:width" content="600" />
    <meta property="og:image:height" content="304" />
    <meta property="og:description" content="{{config('amer.html.description') ?? 'Sinai water'}}" />
    <meta property="og:determiner" content="the" />
    <meta property="og:locale" content="{{config('amer.lang') ?? 'ar-eg'}}" />
    <meta property="og:site_name" content="{{config('amer.co_name') ?? 'NSSCWW'}}" />
    <meta name="twitter:title" content="{{config('amer.co_name') ?? 'NSSCWW'}}">
    <meta name="twitter:description" content=" {{config('amer.html.description') ?? 'Sinai Water'}}">
    <meta name="twitter:image" content="{{asset(config('amer.co_logo')) ?? ''}}">
    <meta name="twitter:card" content="{{asset(config('amer.co_logo')) ?? ''}}">
    <meta charset="{{config('amer.ENCODE') ?? 'UTF-8'}}">
    <meta name="title" content="{{config('amer.co_name') ?? 'HCWW'}}">
    <meta name="description" content="{{config('amer.html.description') ?? ''}}">
    <meta name="keywords" content="{{config('amer.html.keywords') ?? 'HCWW'}}">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset={{config('amer.ENCODE')}}">
    <meta name="language" content="Arabic">
    <meta name="revisit-after" content="7 days">
    <meta name="author" content="amer hendy">
    <meta name="generator" content="amer hendy"/>
    <meta name="referrer" content="origin"/>
    <meta name="referrer" content="origin-when-crossorigin"/>
    <meta name="referrer" content="origin-when-cross-origin"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{asset ('images/logo.png')}}" rel="icon">
<link href="{{asset ('images/logo.png')}}" rel="apple-touch-icon">
<!-- inc.head.headmeta -->