@extends(Baseview('standaloneapp'))
@push('after_scripts')
    @loadScriptOnce('js/jquery/jquery-ui.min.js')
    @loadScriptOnce('js/packages/barryvdh/elfinder/js/elfinder.min.js')
    @if($locale)@loadScriptOnce('js/packages/barryvdh/elfinder/js/i18n/elfinder.'.$locale.'.js')@endif
    <script type="text/javascript" charset="utf-8">
            // Helper function to get parameters from the query string.
            function getUrlParam(paramName) {
                var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
                var match = window.location.search.match(reParam) ;

                return (match && match.length > 1) ? match[1] : '' ;
            }

            $().ready(function() {
                var funcNum = getUrlParam('CKEditorFuncNum');

                var elf = $('#elfinder').elfinder({
                    // set your elFinder options here
                    @if($locale)
                        lang: '{{ $locale }}', // locale
                    @endif
                    customData: { 
                        _token: '{{ csrf_token() }}'
                    },
                    url: '{{ route("elfinder.connector") }}',  // connector URL
                    rememberLastDir:false,
                    useBrowserHistory:false,
                    validName: /^[^\s]$/,
                    soundPath: '{{ asset($dir.'/sounds') }}',
                    getFileCallback : function(file) {
                        window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                        window.close();
                    },
                    debug : ['error', 'warning', 'event-destroy']
                }).elfinder('instance');
            });
        </script>
@endpush
@push('after_styles')
    @loadStyleOnce('css/jquery-ui.css')
    @loadStyleOnce('js/packages/barryvdh/elfinder/css/elfinder.min.css')
    @loadStyleOnce('js/packages/barryvdh/elfinder/css/theme.css')
    <style>
    </style>
    @endpush
        @section('content')
        <div id="elfinder"></div>
        @endsection
        