<!-- wysiwyg-->
@php
    $field['extra_plugins']=[
                    'a11yhelp',
                    'adobeair','ajax','autocomplete','autoembed','autogrow','autolink','balloonpanel','balloontoolbar',
                    'bbcode','bidi','clipboard','codesnippet','codesnippetgeshi','colorbutton','colordialog','copyformatting','devtools','dialog',
                    'dialogadvtab','div','divarea','docprops','editorplaceholder','embed','embedbase','embedsemantic',
                    'emoji','exportpdf','find','font','forms','iframe','iframedialog','image','image2','justify','link',
                    'liststyle','magicline','mentions','newpage','pagebreak','panelbutton','pastefromgdocs',
                    'pastefromlibreoffice','pastefromword','pastetools','placeholder','preview','print','scayt','selectall','showblocks','smiley','sourcedialog','specialchar',
                    'stylesheetparser','table','tableresize','tableselection','tabletools','templates','textmatch','textwatcher','uicolor','widget','wsc','xml'
                ];
    $field['extra_plugins'] = isset($field['extra_plugins']) ? implode(',', $field['extra_plugins']) : "embed,widget";
    $defaultOptions = [
        "filebrowserBrowseUrl" => Amerurl('elfinder/ckeditor'),
        "extraPlugins" => $field['extra_plugins'],
        "embed_provider" => "//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}",
    ];
    $field['options'] = array_merge($defaultOptions, $field['options'] ?? []);
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
<textarea
        name="{{ $field['name'] }}"
        data-init-function="bpFieldInitCKEditorElement"
        data-options="{{ trim(json_encode($field['options'])) }}"
        @include(fieldview('inc.attributes'), ['default_class' => 'form-control'])
    	>{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}</textarea>
        
        @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include(fieldview('inc.wrapper_end'))
    @push('after_scripts')
    @loadScriptOnce('js/packages/ckeditor/ckeditor.js')
    @loadScriptOnce('js/packages/ckeditor/adapters/jquery.js')
    @loadOnce('bpFieldInitCKEditorElement')
    <script>
            function bpFieldInitCKEditorElement(element) {
                element.on('AmerField.deleted', function(e) {
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