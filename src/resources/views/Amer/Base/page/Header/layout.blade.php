@include(mainview('Header.header_a'))
    @include(mainview('Header.header_menu'))
    @section('header')
        @yield('before_breadcrumbs_widgets')
            
            @includeWhen(isset($breadcrumbs), mainview('Header.breadcrumbs'))
        @yield('after_breadcrumbs_widgets')
    @yield('header')