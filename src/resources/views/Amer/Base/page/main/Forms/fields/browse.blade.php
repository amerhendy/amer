<!-- browse.blade -->
@php
@endphp
<div class="col-sm">
	<div class="input-group">
		<input
			type="text"
			name="{{ $field['name'] }}"
			value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
			data-init-function="bpFieldInitBrowseElement"
			data-elfinder-trigger-url="{{ url(config('elfinder.route.prefix').'/popup') }}"
			@include(fieldview('inc.attributes'))
			@if(!isset($field['readonly']) || $field['readonly']) readonly @endif
		>
		<span class="input-group-append">
			<button type="button" data-inputid="{{ $field['name'] }}-filemanager" class="btn btn-light btn-sm popup_selector"><i class="fa fa-cloud-upload"></i></button>
			<button type="button" data-inputid="{{ $field['name'] }}-filemanager" class="btn btn-light btn-sm clear_elfinder_picker"><i class="fa fa-eraser"></i></button>
		</span>
		</div></div>
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
		@loadScriptOnce('js/packages/colorbox/jquery.colorbox-min.js')
		@loadOnce('bpFieldInitBrowseElement')
		<script type="text/javascript">
			// this global variable is used to remember what input to update with the file path
			// because elfinder is actually loaded in an iframe by colorbox
			var elfinderTarget = false;

			// function to update the file selected by elfinder
			function processSelectedFile(filePath, requestingField) {
				elfinderTarget.val(filePath.replace(/\\/g,"/"));
				elfinderTarget = false;
			}

			function bpFieldInitBrowseElement(element) {
				var triggerUrl = element.data('elfinder-trigger-url')
				var name = element.attr('name');

				element.siblings('.input-group-append').children('button.popup_selector').click(function (event) {
				    event.preventDefault();

				    elfinderTarget = element;

				    // trigger the reveal modal with elfinder inside
				    $.colorbox({
				        href: triggerUrl + '/' + name,
				        fastIframe: true,
				        iframe: true,
				        width: '80%',
				        height: '80%'
				    });
				});

				element.siblings('.input-group-append').children('button.clear_elfinder_picker').click(function (event) {
				    event.preventDefault();
				    element.val("");
				});
			}
			@endLoadOnce
		</script>
	@endpush
