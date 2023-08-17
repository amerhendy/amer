<!-- inc.footersc -->
<script type="application/javascript">
var websitelink="{{url('')}}";
var api=websitelink+'api/'
</script>
    @foreach (config('amer.mainscripts') as $path)
    @loadScriptOnce($path)
    @endforeach
<script>
    /*function aos_init() {
    AOS.init({
        duration: 1000,
        once: true
    });
}*/
</script>
@yield('scripts')
@stack('scripts')
@yield('before_scripts')
@stack('before_scripts')
@yield('after_scripts')
@stack('after_scripts')