<!-- inc.head.headmeta -->
    <title>{{$page_title ?? config('Amer.Amer.co_name') ?? 'Amer'}} :: {{config('Amer.Amer.co_name') ?? 'Amer'}} </title>
    <base href="{{url('')}}">
    <meta name="theme-color" content    ="{{config('Amer.Amer.html.theme-color') ?? 'white'}}">
    <meta name="description" content="{{config('Amer.Amer.html.description') ?? 'AmerHendy'}}" />
    <meta property="og:title" content="{{config('Amer.Amer.co_name') ?? 'Amer'}}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{url('')}}" />
    <meta property="og:image" content="{{asset(config('Amer.Amer.co_logo')) ?? ''}}" />
    <meta property="og:image:width" content="600" />
    <meta property="og:image:height" content="304" />
    <meta property="og:description" content="{{config('Amer.Amer.html.description') ?? 'AmerHendy'}}" />
    <meta property="og:determiner" content="the" />
    <meta property="og:locale" content="{{config('Amer.Amer.lang') ?? 'ar-eg'}}" />
    <meta property="og:site_name" content="{{config('Amer.Amer.co_name') ?? 'AMER'}}" />
    <meta name="twitter:title" content="{{config('Amer.Amer.co_name') ?? 'AMER'}}">
    <meta name="twitter:description" content=" {{config('Amer.Amer.html.description') ?? 'AmerHendy'}}">
    <meta name="twitter:image" content="{{asset(config('Amer.Amer.co_logo')) ?? ''}}">
    <meta name="twitter:card" content="{{asset(config('Amer.Amer.co_logo')) ?? ''}}">
    <meta charset="{{config('Amer.Amer.ENCODE') ?? 'UTF-8'}}">
    <meta name="title" content="{{config('Amer.Amer.co_name') ?? 'Amer'}}">
    <meta name="description" content="{{config('Amer.Amer.html.description') ?? ''}}">
    <meta name="keywords" content="{{config('Amer.Amer.html.keywords') ?? 'Amer'}}">
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
    @if(config('Amer.Amer.browser_cache.cache-control') !== false)
        <meta http-equiv='cache-control' content="{{config('Amer.Amer.browser_cache.cache-control')}}">
    @endif
    @if(config('Amer.Amer.browser_cache.expires') !== false)
        <meta http-equiv='expires' content="{{config('Amer.Amer.browser_cache.expires')}}">
    @endif
    @if(config('Amer.Amer.browser_cache.pragma') !== false)
        <meta http-equiv='pragma' content="{{config('Amer.Amer.browser_cache.pragma')}}">
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset ('images/logo.png') ?? ''}}" rel="icon">
    <link href="{{asset ('images/logo.png') ?? ''}}" rel="apple-touch-icon">

@if(config('Amer.Amer.mainstyle') !== null)
    @foreach (config('Amer.Amer.mainstyle') as $path)
    @php
        $csspath=$path['url']."?v=".config('Amer.Amer.cachebusting_string');
        $cssmediatype=$path['media'];
    @endphp
    @loadStyleOnce($csspath,$cssmediatype)
    @endforeach
@endif
<!-- font -->
<style>
    :root,
    [data-bs-theme=light] {
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
@loadStyleOnce('css/app.css')
<!-- inc.head.headmeta -->
