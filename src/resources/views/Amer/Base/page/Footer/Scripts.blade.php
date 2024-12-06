<!-- inc.footersc -->
<?php
//dd(convert_uuencode(json_encode([convert_uuencode(env("API_CLIENT_ID") ?? ""),convert_uuencode(env("API_CLIENT_SECRET") ?? "")])));
?>
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
    @foreach (config('Amer.Amer.mainscripts') as $path)
    @loadScriptOnce($path)
    @endforeach
<script  type="application/javascript" aria-describedat="injectedScript">
    //injectedScript
    @yield('inject_scripts')
    @stack('inject_scripts')
    //injectedScript
</script>
@yield('scripts')
@stack('scripts')
@yield('before_scripts')
@stack('before_scripts')
@yield('after_scripts')
@stack('after_scripts')
