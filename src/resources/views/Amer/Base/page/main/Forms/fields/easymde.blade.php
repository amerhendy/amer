<!-- easymde -->
<?php
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
    $field['minimum_input_length']=$field['minimum_input_length'] ?? 0 ;
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <textarea
        name="{{ $field['name'] }}"
        minlength="{{$field['minimum_input_length']}}"
        placeholder="{{ $field['placeholder'] }}"
        data-init-function="bpFieldInitEasyMdeElement"
        data-easymdeAttributesRaw="{{ isset($field['easymdeAttributesRaw']) ? "{".$field['easymdeAttributesRaw']."}" : "{}" }}"
        data-easymdeAttributes="{{ isset($field['easymdeAttributes']) ? json_encode($field['easymdeAttributes']) : "{}" }}"
        @include(fieldview('inc.attributes'), ['default_class' => 'form-control'])
    	>{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}</textarea>
@if (isset($field['hint']))
    <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
@endif
@include(fieldview('inc.wrapper_end'))
    @push('after_styles')
    @loadStyleOnce('asd/easymde/dist/easymde.min.css')
        @loadOnce('easymde.style')
        <style type="text/css">
            .editor-toolbar {
                border: 1px solid #ddd;
                border-bottom: none;
            }
        </style>
        @endLoadOnce
    @endpush
    @push('after_scripts')
    @loadScriptOnce('asd/easymde/dist/easymde.min.js')
    @loadOnce('bpFieldInitEasyMdeElement')
        <script>
            function bpFieldInitEasyMdeElement(element) {
                if (element.attr('data-initialized') == 'true') {
                    return;
                }

                if (typeof element.attr('id') == 'undefined') {
                    element.attr('id', 'EasyMDE_'+Math.ceil(Math.random() * 1000000));
                }

                var elementId = element.attr('id');
                var easymdeAttributes = JSON.parse(element.attr('data-easymdeAttributes'));
                var easymdeAttributesRaw = JSON.parse(element.attr('data-easymdeAttributesRaw'));
                var configurationObject = {
                    element: document.getElementById(elementId),
                };

                configurationObject = Object.assign(configurationObject, easymdeAttributes, easymdeAttributesRaw);

                if (!document.getElementById(elementId)) {
                    return;
                }

                var easyMDE = new EasyMDE(configurationObject);

                easyMDE.options.minHeight = easyMDE.options.minHeight || "300px";
                easyMDE.codemirror.getScrollerElement().style.minHeight = easyMDE.options.minHeight;

                // update the original textarea on keypress
                easyMDE.codemirror.on("change", function(){
                    element.val(easyMDE.value());
                });
                /*
                 $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                     setTimeout(function() { easyMDE.codemirror.refresh(); }, 10);
                 });*/
                 var btns=$('.editor-toolbar').children();
                 $(btns).each(function(k,v){
                    if(v['localName'] == 'button'){
                        $(v).addClass("btn");
                    }
                 });
            }
        </script>
        @endLoadOnce
    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
