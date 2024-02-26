<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <title>elFinder 2.0</title>
        @loadStyleOnce('css/jquery-ui.css')
        @loadStyleOnce('js/packages/barryvdh/elfinder/css/elfinder.min.css')
        @loadStyleOnce('js/packages/barryvdh/elfinder/css/theme.css')
        <!-- jQuery and jQuery UI (REQUIRED) -->
        @loadScriptOnce('js/jquery/jquery-3.6.0.min.js')
    @loadScriptOnce('js/jquery/jquery-ui.min.js')
    @loadScriptOnce('js/packages/barryvdh/elfinder/js/elfinder.min.js')
    @if($locale)@loadScriptOnce('js/packages/barryvdh/elfinder/js/i18n/elfinder.'.$locale.'.js')@endif

        <script type="text/javascript">
            $().ready(function () {
                var elf = $('#elfinder').elfinder({
                    cssAutoLoad : false,
                    speed: 100,
                    // set your elFinder options here
                    @if($locale)
                        lang: '{{ $locale }}', // locale
                    @endif
                    customData: { 
                        _token: '{{ csrf_token() }}'
                    },
                    url: '{{ route("elfinder.connector") }}',  // connector URL
                    soundPath: '{{ asset($dir.'/sounds') }}',
                    dialog: {width: 900, modal: true, title: 'Select a file'},
                    onlyMimes: @json(unserialize(urldecode(request('mimes'))), JSON_UNESCAPED_SLASHES),
                    resizable: false,
                    commandsOptions: {
                        getfile: {
                            multiple: {{ request('multiple') ? 'true' : 'false' }},
                            oncomplete: 'destroy'
                        }
                    },
                    validName: /^[^\s]$/,
                    getFileCallback: function (file) {
                        @if (request()->has('multiple') && request()->input('multiple') == 1)
                            window.parent.processSelectedMultipleFiles(file, '{{ $input_id  }}');
                        @else
                            window.parent.processSelectedFile(file.path, '{{ $input_id  }}');
                        @endif
                        window.parent.jQuery.colorbox.close();
                    },
                }).elfinder('instance');
            });
        </script>

    </head>
    <body>

        <!-- Element where elFinder will be created (REQUIRED) -->
        <div id="elfinder"></div>

    </body>
</html>
