<!-- Simple MDE - Markdown Editor -->
<?php
if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
?>
@include(fieldview('inc.wrapper_start'))
    <label>{!! $field['label'] !!}</label>
    @include(fieldview('inc.translatable_icon'))
    <textarea
        name="{{ $field['name'] }}"
        id="{{ $field['name'] }}"
        placeholder="{{ $field['placeholder'] }}"
        data-init-function="bpFieldInitSimpleMdeElement"
        data-simplemdeAttributesRaw="{{ isset($field['simplemdeAttributesRaw']) ? "{".$field['simplemdeAttributesRaw']."}" : "{}" }}"
        data-simplemdeAttributes="{{ isset($field['simplemdeAttributes']) ? json_encode($field['simplemdeAttributes']) : "{}" }}"
        @include(fieldview('inc.attributes'), ['default_class' => 'form-control'])
    	>{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}</textarea>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
    @include(fieldview('inc.wrapper_end'))
    @push('after_styles')
    @loadStyleOnce('js/packages/simplemde/dist/simplemde.min.css')
    @loadOnce('CodeMirror')
        <style type="text/css">
        .CodeMirror-fullscreen, .editor-toolbar.fullscreen {
            z-index: 9999 !important;
        }
        .CodeMirror{
        	min-height: auto !important;
        }
        </style>
    @endLoadOnce
    @endpush
    @push('after_scripts')
    @loadScriptOnce('js/packages/simplemde/dist/simplemde.min.js')
    @loadOnce('bpFieldInitSimpleMdeElement')
        <script>
            function bpFieldInitSimpleMdeElement(element) {
                if (element.attr('data-initialized') == 'true') {
                    return;
                }
                if (typeof element.attr('id') == 'undefined') {
                    element.attr('id', 'SimpleMDE_'+Math.ceil(Math.random() * 1000000));
                }

                var elementId = element.attr('id');
                var simplemdeAttributes = JSON.parse(element.attr('data-simplemdeAttributes'));
                var simplemdeAttributesRaw = JSON.parse(element.attr('data-simplemdeAttributesRaw'));
                var configurationObject = {
                    element: document.getElementById(elementId),
                    autoDownloadFontAwesome:false,
                    spellChecker:false,
                    placeholder: "Type here...",
                    previewRender: function(plainText) {
                        return customMarkdownParser(plainText); // Returns HTML from a custom parser
                    },
                    previewRender: function(plainText, preview) { // Async method
                        setTimeout(function(){
                            preview.innerHTML = customMarkdownParser(plainText);
                        }, 250);

                        return "Loading...";
                    },
                    promptURLs: true,
                    renderingConfig: {
                        singleLineBreaks: false,
                        codeSyntaxHighlighting: true,
                    },
                    shortcuts: {
                        drawTable: "Cmd-Alt-T"
                    },
                    tabSize: 4,
                };

                configurationObject = Object.assign(configurationObject, simplemdeAttributes, simplemdeAttributesRaw);

                if (!document.getElementById(elementId)) {
                    return;
                }

                var smdeObject = new SimpleMDE(configurationObject);
                var toolbar=$('#'+elementId).closest('div').find('.editor-toolbar');
                var bold=$(toolbar).find('.fa-bold');$(bold).html("<i class='fa fa-bold'></i>");$(bold).removeClass('fa-bold');$(bold).removeClass('fa')
                var italic=$(toolbar).find('.fa-italic');$(italic).html("<i class='fa fa-italic'></i>");$(italic).removeClass('fa-italic');$(italic).removeClass('fa')
                var header=$(toolbar).find('.fa-header');$(italic).html("<i class='fa fa-header'></i>");$(header).removeClass('fa-header');$(header).removeClass('fa')
                var quote_left=$(toolbar).find('.fa-quote-left');$(quote_left).html("<i class='fa fa-quote-left'></i>");$(quote_left).removeClass('fa-quote-left');$(quote_left).removeClass('fa')
                var list_ul=$(toolbar).find('.fa-list-ul');$(list_ul).html("<i class='fa fa-list-ul'></i>");$(list_ul).removeClass('fa-list-ul');$(list_ul).removeClass('fa')
                var list_ol=$(toolbar).find('.fa-list-ol');$(list_ol).html("<i class='fa fa-list-ol'></i>");$(list_ol).removeClass('fa-list-ol');$(list_ol).removeClass('fa')
                var link=$(toolbar).find('.fa-link');$(link).html("<i class='fa fa-link'></i>");$(link).removeClass('fa-link');$(link).removeClass('fa')
                var picture_o=$(toolbar).find('.fa-picture-o');$(picture_o).html("<i class='fa fa-picture-o'></i>");$(picture_o).removeClass('fa-picture-o');$(picture_o).removeClass('fa')
                var eye=$(toolbar).find('.fa-eye');$(eye).html("<i class='fa fa-eye'></i>");$(eye).removeClass('fa-eye');$(eye).removeClass('fa')
                dd()
                smdeObject.options.minHeight = smdeObject.options.minHeight || "300px";
                smdeObject.codemirror.getScrollerElement().style.minHeight = smdeObject.options.minHeight;

                // update the original textarea on keypress
                smdeObject.codemirror.on("change", function(){
                    element.val(smdeObject.value());
                });

                $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    setTimeout(function() { smdeObject.codemirror.refresh(); }, 10);
                });
            }
        </script>
        @endLoadOnce
    @endpush
