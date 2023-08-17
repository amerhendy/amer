@if (!empty($widgets))
	@foreach ($widgets as $currentWidget)
		@if (is_array($currentWidget))
			@php
				$currentWidget = \Amerhendy\Amer\App\Helpers\Widget::add($currentWidget);
			@endphp
		@endif
		@include($currentWidget->getFinalViewPath(), ['widget' => $currentWidget->toArray()])
	@endforeach
@endif
