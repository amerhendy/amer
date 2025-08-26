<?php
//dd(convert_uuencode(json_encode([convert_uuencode(env("API_CLIENT_ID") ?? ""),convert_uuencode(env("API_CLIENT_SECRET") ?? "")])));
$website=\Str::finish(config('app.url'),'/');
$apilinks=\Str::finish($website.'api/'.config("Amer.Amer.api_version")??"",'/');
?>
<!-- app -->
<!DOCTYPE html>
<html lang="{{config('Amer.Amer.lang') ?? 'ar-eg'}}" dir="{{config('Amer.Amer.html_direction') ?? 'rtl'}} " prefix="{{config('Amer.Amer.co_name') ?? 'HCWW'}}" data-bs-theme="auto">
@stack('beforehead')
<head>
    @include(mainview('head.meta'))
@section('header-meta')
    @show
    @include(mainview('head.styles'))
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
<?php
	if (isset($widgets)) {
		foreach ($widgets as $section => $widgetSection) {
			foreach ($widgetSection as $key => $widget) {
				\Amerhendy\Amer\App\Helpers\Widget::add($widget)->section($section);
			}
		}
	}
?>
<header class="section page-header">
@include(mainview('Header.layout'))
</header>
<main class="section fixed container-fluid">
    <div class="row">
    @php
   @endphp
        @if(Auth::guard('Amer')->check())
        <div class="col-sm-3">
            @include(mainview('SideBar.layout'))
        </div>
        <div class="col-sm-9">
        @else
        <div class="col-sm-12">
        @endif
            @include(mainview('main.Alert'))
            <div class="container-fluid">
            @section('before_content_widgets')
            @include(mainview('main.Widgets'), [ 'widgets' => app('widgets')->where('section', 'before_content')->toArray() ])
        @endsection
            @yield('before_content_widgets')
          @yield('content')
          @yield('after_content_widgets')

    </div>
        </div>
    </div>


<!-- app.blade.php -->


	<!-- app.blade.php -->
</main>
<!-- app.blade.php -->
<footer class="nsscwwbgcolor text-center text-lg-start page-footer font-small">
    {{--@include(mainview('Footer.footer')) --}}
</footer>
<!-- app.blade.php -->
@yield('AFTERFOOTER')
@stack('AFTERFOOTER')
</body>
@include(mainview('Footer.Scripts'))
</html>
