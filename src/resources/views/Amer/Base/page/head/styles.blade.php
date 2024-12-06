<!-- inc.headsc -->
<link rel="alternate" type="application/rss+xml" title="Simplest Web" href="{{url('rss') ?? ''}}" />
@yield('before_styles')
    @stack('before_styles')
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
@if (config('Amer.Amer.mix_styles') && count(config('Amer.Amer.mix_styles')))
        @foreach (config('Amer.Amer.mix_styles') as $path => $manifest)
        <link rel="stylesheet" type="text/css" href="{{ mix($path, $manifest) }}">
        @endforeach
    @endif
@yield('after_styles')
@stack('after_styles')
<!-- inc.headsc -->
