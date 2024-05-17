<!-- widgetview.view -->
{{-- view field --}}
@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_start'))
	
	@include($widget['view'], ['widget' => $widget])

	@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_end'))