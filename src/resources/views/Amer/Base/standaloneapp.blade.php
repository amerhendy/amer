<!-- app -->
<!DOCTYPE html>
<html lang="{{config('amer.lang') ?? 'ar-eg'}}" dir="{{config('amer.lang') ?? 'rtl'}} " prefix="{{config('amer.co_name') ?? 'HCWW'}}" data-bs-theme="auto">
@stack('beforehead')
<head>
@include('Amer::Base.inc.head.headmeta')
@section('header-meta')
    @show
@include('Amer::Base.inc.head.headsc')
</head>
@stack('beforebody')
<body>
@stack('afterbody')
@php
	if (isset($widgets)) {
		foreach ($widgets as $section => $widgetSection) {
			foreach ($widgetSection as $key => $widget) {
				\Amerhendy\Amer\App\Helpers\Widget::add($widget)->section($section);
			}
		}
	}
@endphp
<header class="section page-header">
    @include('Amer::Base.inc.header.header_a')
    @include('Amer::Base.inc.header.header_menu')
    @section('header')
        @yield('before_breadcrumbs_widgets')
            @includeWhen(isset($breadcrumbs), baseview('inc.breadcrumbs'))
        @yield('after_breadcrumbs_widgets')
    @yield('header')
</header>
<main class="section fixed container-fluid">
@include('Amer::Base.inc.main.alert')
<!-- app.blade.php -->
    <div class="container-fluid">
        @section('before_content_widgets')
            @include(Baseview('inc.main.widgets'), [ 'widgets' => app('widgets')->where('section', 'before_content')->toArray() ])
        @endsection
            @yield('before_content_widgets')
          @yield('content')
          @yield('after_content_widgets')
          
    </div>
	<!-- app.blade.php -->
</main>
<!-- app.blade.php -->
<footer class="bg-dark text-center text-lg-start page-footer font-small">
    @include('Amer::Base.inc.footer.footer')
</footer>
<!-- app.blade.php -->
@yield('AFTERFOOTER')
@stack('AFTERFOOTER')
</body>
@include('Amer::Base.inc.footer.footersc')
</html>
