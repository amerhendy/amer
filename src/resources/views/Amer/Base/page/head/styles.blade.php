<!-- inc.headsc -->
<link rel="alternate" type="application/rss+xml" title="Simplest Web" href="{{url('rss') ?? ''}}" />
@yield('before_styles')
    @stack('before_styles')
@if(config('Amer.amer.mainstyle') !== null)
    @foreach (config('Amer.amer.mainstyle') as $path)
    @php
        $csspath=$path['url']."?v=".config('amer.cachebusting_string');
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
@if (config('Amer.amer.mix_styles') && count(config('Amer.amer.mix_styles')))
        @foreach (config('Amer.amer.mix_styles') as $path => $manifest)
        <link rel="stylesheet" type="text/css" href="{{ mix($path, $manifest) }}">
        @endforeach
    @endif
@yield('after_styles')
    @stack('after_styles')
<!-- inc.headsc -->