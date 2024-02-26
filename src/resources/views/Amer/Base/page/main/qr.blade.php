@extends(Baseview('app'))
@section('header')
	<section class="container-fluid">
	</section>
@endsection
@section('content')
<input type="text" id="target-input"/>
<input type="button" id="openreader-btn" 
  data-qrr-target="#target-input" 
  data-qrr-audio-feedback="false" 
  value="Scan QRCode"/>
@endsection
@push('after_scripts')
@loadStyleOnce('js/packages/qrcode-reader-master/dist/css/qrcode-reader.min.css')
@endpush
@push('after_scripts')
@loadScriptOnce('js/packages/qrcode-reader-master/dist/js/qrcode-reader.min.js')
<script>
    //alert("{{asset('js/packages/qrcode-reader-master/dist/audio/beep.mp3')}}")
    $.qrCodeReader.jsQRpath = "{{asset('js/packages/qrcode-reader-master/dist/js/jsQR/jsQR.min.js')}}";
    $.qrCodeReader.beepPath = "{{asset('js/packages/qrcode-reader-master/dist/audio/beep.mp3')}}";
    $("#openreader-btn").qrCodeReader();
</script>
@endpush