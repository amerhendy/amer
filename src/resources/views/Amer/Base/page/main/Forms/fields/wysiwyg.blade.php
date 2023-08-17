<!-- wysiwyg-->
@php
    $field['extra_plugins'] = isset($field['extra_plugins']) ? implode(',', $field['extra_plugins']) : "embed,widget";

    $defaultOptions = [
        "filebrowserBrowseUrl" => Amerurl('elfinder/ckeditor'),
        "extraPlugins" => $field['extra_plugins'],
        "embed_provider" => "//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}",
    ];

    $field['options'] = array_merge($defaultOptions, $field['options'] ?? []);
@endphp

<textarea
        name="{{ $field['name'] }}"
        data-init-function="bpFieldInitCKEditorElement"
        data-options="{{ trim(json_encode($field['options'])) }}"
        @include(fieldview('inc.attributes'), ['default_class' => 'form-control'])
    	>{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}</textarea>
    @push('after_scripts')
    @loadScriptOnce('js/packages/ckeditor/ckeditor.js')
    @loadScriptOnce('js/packages/ckeditor/adapters/jquery.js')
    @loadOnce('bpFieldInitCKEditorElement')
    <script>
            function bpFieldInitCKEditorElement(element) {
                element.on('backpack_field.deleted', function(e) {
                    $ck_instance_name = element.siblings("[id^='cke_editor']").attr('id');
                    if($ck_instance_name.startsWith('cke_')) {
                        $ck_instance_name = $ck_instance_name.substr(4);
                    }
                    CKEDITOR.instances[$ck_instance_name].destroy(true);
                });
                element.ckeditor(element.data('options'));
            }
    </script>
    @endLoadOnce
    @endpush