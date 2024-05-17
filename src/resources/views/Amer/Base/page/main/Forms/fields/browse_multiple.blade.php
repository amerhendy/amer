<!-- browse.blade -->
<?php
$multiple = Arr::get($field, 'multiple', true);
$value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
if (!$multiple && is_array($value)) {
    $value = Arr::first($value);
}
?>
<div class="list row" data-field-name="{{ $field['name'] }}">
        <input type="hidden" data-marker="multipleBrowseInput" name="{{ $field['name'] }}" value="{{ json_encode($value) }}" data-multiple="true" multiple>
</div>
<div class="col-sm">
	<div class="input-group">
		<input
			type="hidden"
			name="{{ $field['name'] }}"
			value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
			data-elfinder-trigger-url="{{ url(config('elfinder.route.prefix').'/popup') }}"
			@include(fieldview('inc.attributes'))
			@if(!isset($field['readonly']) || $field['readonly']) readonly @endif
            data-multiple="true" multiple
            data-marker="multipleBrowseInput"
		>
		<span class="input-group-append">
			<button type="button" data-inputid="{{ $field['name'] }}-filemanager" class="btn btn-light btn-sm popup_selector"><i class="fa fa-cloud-upload"></i></button>
			<button type="button" data-inputid="{{ $field['name'] }}-filemanager" class="btn btn-light btn-sm clear_elfinder_picker"><i class="fa fa-eraser"></i></button>
		</span>
		</div></div>

        <template data-marker="browse_multiple_template">
        <div class="col-sm-3 mb-3 mb-sm-0" id='fileTemplate'>
            <div class="card">
                <img class="card-img-top">
                <div class="card-body">
                <input type="hidden" @include(fieldview('inc.attributes')) readonly>
                <h6 class="card-title"></h6>
                <button type="button" class="browse remove btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                <button type="button" class="browse move btn btn-sm btn-success"><span class="fa fa-sort"></span></button>
                </div>
            </div>
        </div>        
    </template>

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
		<!-- include browse server js -->
        @loadScriptOnce('js/jquery/jquery-ui.min.js')
		@loadScriptOnce('js/packages/colorbox/jquery.colorbox-min.js')
		@loadOnce('bpFieldInitBrowseMultipleElement')
		<script type="text/javascript">
			// this global variable is used to remember what input to update with the file path
			// because elfinder is actually loaded in an iframe by colorbox
			var elfinderTarget = false;

			// function to update the file selected by elfinder
			function processSelectedMultipleFiles(files, requestingField) {
                elfinderTarget.trigger('createInputsForItemsSelectedWithElfinder', [files]);
				elfinderTarget = false;
			}
			function bpFieldInitBrowseMultipleElement(element) {
				var triggerUrl = element.attr('elfinder-trigger-url')
                var $input = element.find('input[data-marker=multipleBrowseInput]');
                var $template = element.find("[data-marker=browse_multiple_template]").html();
                var $list = element.find(".list");
                var $sortable = element.attr('sortable');
                var $multiple = element.attr('data-multiple');
                var $field_name=$input.attr('name')
                $list.sortable({
                        handle: 'button.move',
                        cancel: '',
                        update: function (event, ui) {
                            element.trigger('saveToJson');
                        }
                    });
				element.find('.input-group-append').children('button.popup_selector').click(function (event) {
				    event.preventDefault();
				    elfinderTarget = element;
				    $.colorbox({
				        href: triggerUrl,
				        fastIframe: true,
				        iframe: true,
				        width: '80%',
				        height: '80%'
				    });
				});
                element.on('saveToJson', function(event) {
                    $INPUS=element.find('input').not('[name='+$field_name+']');
                    var allurls=new Array();
                    $.each($INPUS,function(idx,item){
                        allurls.push($(item).val())
                    })
                    $input.val(JSON.stringify(allurls));
                });

                if ($multiple === 'true') {
                    // remote item button
                    element.on('click', 'button.remove', function (event) {
                        event.preventDefault();
                        $(this).closest('#fileTemplate').remove();
                        element.trigger('saveToJson');
                    });
                    // clear button
                    element.on('click', 'button.clear_elfinder_picker', function (event) {
                        event.preventDefault();
                        $('.col-sm-3', $list).remove();
                        element.trigger('saveToJson');
                    });

                    // called after one or more items are selected in the elFinder window
                    element.on('createInputsForItemsSelectedWithElfinder', element, function(event, files) {
                        files.forEach(function (file) { 
                            var newInput = $($template);
                            newInput.find('input').val(file.url);
                            newInput.find('.card-title').html(file.name)
                            if(file.mime.includes('image')){
                                imginput=newInput.find('img');
                                imginput.attr('width',113)
                                imginput.attr('src',file.url)
                            }
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