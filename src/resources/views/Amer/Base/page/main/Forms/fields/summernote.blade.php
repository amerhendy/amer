{{-- summernote editor --}}
@php
    // make sure that the options array is defined
    // and at the very least, dialogsInBody is true;
    // that's needed for modals to show above the overlay in Bootstrap 4
    $field['options'] = array_merge(['dialogsInBody' => true, 'tooltip' => false,'focus'=> true], $field['options'] ?? []);
    
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <textarea
    name="{{ $field['name'] }}"
    id="{{ $field['name'] }}"
        data-init-function="bpFieldInitSummernoteElement"
        data-options="{{ json_encode($field['options']) }}"
        bp-field-main-input
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control summernote'])
        >{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}</textarea>
        
        @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))

    {{-- HINT --}}
    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@push('after_styles')
    @loadStyleOnce('js/packages/summernote/summernote-bs4.css')
    
@loadOnce('summernoteCss')
    <style type="text/css">
        .note-editor.note-frame .note-status-output, .note-editor.note-airframe .note-status-output {
                height: auto;
        }
    </style>
@endLoadOnce
@endpush
{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('after_scripts')
    @loadScriptOnce('js/packages/summernote/summernote-bs4.min.js')
    @loadOnce('bpFieldInitSummernoteElement')
    <script>
        function bpFieldInitSummernoteElement(element) {
             var summernoteOptions = element.data('options');

            let summernotCallbacks = { 
                onChange: function(contents, $editable) {
                    element.val(contents).trigger('change');
                }
            }

            element.on('AmerField:disable', function(e) {
                element.summernote('disable');
            });

            element.on('AmerField:enable', function(e) {
                element.summernote('enable');
            });
            
            summernoteOptions['callbacks'] = summernotCallbacks;
            
            element.summernote(summernoteOptions); 
        }
    </script>
    @endLoadOnce
@endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
