<!-- inc.footersc -->
<?php
//dd(convert_uuencode(json_encode([convert_uuencode(env("API_CLIENT_ID") ?? ""),convert_uuencode(env("API_CLIENT_SECRET") ?? "")])));
?>

<script type="application/javascript">
var websitelink="{{$website}}";
var api='{{\Str::finish($website."api/".config("Amer.amer.api_version")??"","/")}}';
const clientInfo=`{{base64_encode(json_encode([base64_encode(env("API_CLIENT_ID") ?? ""),base64_encode(env("API_CLIENT_SECRET") ?? "")]))}}`;
var jstrans=[];
jstrans['error']="{{trans('AMER::errors.ajax_error_title')}}";
</script>
    @foreach (config('Amer.amer.mainscripts') as $path)
    @loadScriptOnce($path)
    @endforeach
@yield('scripts')
@stack('scripts')
@yield('before_scripts')
@stack('before_scripts')
@yield('after_scripts')
@stack('after_scripts')