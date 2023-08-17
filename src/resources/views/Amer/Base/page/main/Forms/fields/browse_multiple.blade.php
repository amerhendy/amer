<!-- browse_multiple.blade -->
@php
$multiple = Arr::get($field, 'multiple', true);
$value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';

if (!$multiple && is_array($value)) {
    $value = Arr::first($value);
}
$field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
$field['wrapper']['data-init-function'] = $field['wrapper']['data-init-function'] ?? 'bpFieldInitBrowseMultipleElement';
$field['wrapper']['data-elfinder-trigger-url'] = $field['wrapper']['data-elfinder-trigger-url'] ?? url(config('elfinder.route.prefix').'/popup/'.$field['name'].'?multiple=1');
$wantedtrigers=['mime_types','rememberLastDir','useBrowserHistory','onlyMimes','clientFormatDate','UTCDate','disk','path'];
$tr='';
forEach($field as $a=>$b){    
    if(in_array($a,$wantedtrigers)){
        if(is_bool($b)){
            if($b == true || $b == "true" || $b == 1){$b="true";}
            if($b == false || $b == "false" || $b == 0){$b="false";}
            $tr.="&".$a.'='.$b;
        }else{
            $tr.="&".$a.'='.urlencode(serialize($b));
        }
        
    }   
}
$field['wrapper']['data-elfinder-trigger-url'].=$tr;
    $field['wrapper']['sortable'] = "true";
$field['wrapper']['data-multiple'] = "true";
@endphp
    <div class="list" data-field-name="{{ $field['name'] }}">
        <input type="hidden" data-marker="multipleBrowseInput" name="{{ $field['name'] }}" value="{{ json_encode($value) }}" data-multiple="true" multiple>
</div>
    <div class="btn-group" role="group" aria-label="..." style="margin-top: 3px;">
        <button type="button" class="browse popup btn btn-sm btn-light">
            <i class="fa fa-cloud-upload"></i>
        </button>
        <button type="button" class="browse clear btn btn-sm btn-light">
            <i class="fa fa-eraser"></i>
        </button>
    </div>

    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

    <script type="text/html" data-marker="browse_multiple_template">
        <div class="input-group input-group-sm">
            <input type="text" @include(fieldview('inc.attributes')) readonly>
            <div class="input-group-btn">
                <button type="button" class="browse remove btn btn-sm btn-outline-danger">
                    <i class="fa fa-trash"></i>
                </button>
                    <button type="button" class="browse move btn btn-sm btn-success"><span class="fa fa-sort"></span></button>
            </div>
        </div>
    </script>
    @push('after_styles')
		<!-- include browse server css -->
		@loadStyleOnce('js/packages/colorbox/colorbox.css')
        @loadOnce('cbox_style')
		<style>
			#cboxContent, #cboxLoadedContent, .cboxIframe {
				background: transparent;
			}
		</style>
		@endLoadOnce
	@endpush
@push('after_scripts')
            @loadScriptOnce('js/jquery/jquery-ui.min.js')
            @loadScriptOnce('js/packages/colorbox/jquery.colorbox-min.js')
            @loadOnce('bpFieldInitBrowseMultipleElement')
        <script>
            // this global variable is used to remember what input to update with the file path
            // because elfinder is actually loaded in an iframe by colorbox
            var elfinderTarget = false;

            // function to use the files selected inside elfinder
            function processSelectedMultipleFiles(files, requestingField) {
                elfinderTarget.trigger('createInputsForItemsSelectedWithElfinder', [files]);                
                elfinderTarget = false;
            }

            function bpFieldInitBrowseMultipleElement(element) {
                var $triggerUrl = element.data('elfinder-trigger-url');
                var $template = element.find("[data-marker=browse_multiple_template]").html();
                var $list = element.find(".list");
                var $input = element.find('input[data-marker=multipleBrowseInput]');
                var $multiple = element.attr('data-multiple');
                var $sortable = element.attr('sortable');
                // show existing items - display visible inputs for each stored path  
                if ($input.val() != '' && $input.val() != null && $multiple === 'true') {
                    $paths = JSON.parse($input.val());
                    if (Array.isArray($paths) && $paths.length) {
                        // remove any already visible inputs
                        $list.find('.input-group').remove();

                        // add visible inputs for each item inside the hidden input array
                        $paths.forEach(function (path) {
                            var newInput = $($template);
                            newInput.find('input').val(path);
                            $list.append(newInput);
                        });
                    }
                }

                // make the items sortable, if configurations says so
                    $list.sortable({
                        handle: 'button.move',
                        cancel: '',
                        update: function (event, ui) {
                            element.trigger('saveToJson');
                        }
                    });

                element.on('click', 'button.popup', function (event) {
                    event.preventDefault();
                    // remember which element the elFinder was triggered by
                    elfinderTarget = element;
                    var volumeId = 'l1_';
                    var hash = volumeId + btoa('{{$field["path"]}}').replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '.').replace(/\.+$/, '');
                    //$triggerUrl+='#'+hash;
                    // trigger the elFinder modal
                    $.colorbox({
                        href: $triggerUrl,
                        fastIframe: true,
                        iframe: true,
                        width: '80%',
                        height: '80%'
                    });
                });

                // turn non-hidden inputs into a JSON
                // and save them inside the hidden input that ACTUALLY holds all paths
                element.on('saveToJson', function(event) {
                    var $paths = element.find('input').not('[type=hidden]').map(function (idx, item) {
                        return $(item).val();
                    }).toArray();

                    // save the JSON inside the hidden input
                    $input.val(JSON.stringify($paths));
                });

                if ($multiple === 'true') {
                    // remote item button
                    element.on('click', 'button.remove', function (event) {
                        event.preventDefault();
                        $(this).closest('.input-group').remove();
                        element.trigger('saveToJson');
                    });

                    // clear button
                    element.on('click', 'button.clear', function (event) {
                        event.preventDefault();

                        $('.input-group', $list).remove();
                        element.trigger('saveToJson');
                    });

                    // called after one or more items are selected in the elFinder window
                    element.on('createInputsForItemsSelectedWithElfinder', element, function(event, files) {
                        files.forEach(function (file) {
                            var newInput = $($template);
                            newInput.find('input').val(file.path);
                            $list.append(newInput);
                        });
                            $list.sortable("refresh")

                        element.trigger('saveToJson');
                    });

                } else {
                    // clear button
                    element.on('click', 'button.clear', function (event) {
                        $input.val('');
                    });

                    // called after an item has been selected in the elFinder window
                    element.on('createInputsForItemsSelectedWithElfinder', element, function(event, files) {
                        $input.val(files[0].path);
                    });
                }
            }
        </script>
        @endLoadOnce
    @endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
