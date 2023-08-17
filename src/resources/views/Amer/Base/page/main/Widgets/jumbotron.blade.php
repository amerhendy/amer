<!-- widgetview.jumbotron -->
@php
/*
[
            'type'=>'jumbotron',
            'wrapperClass'=>'row',
            'heading'=>'title',
            'content'=>'content',
            'button_link'=>'link',
            'button_text'=>'text',
            ]
*/
	// preserve backwards compatibility with Widgets in Backpack 4.0
	if (isset($widget['wrapperClass'])) {
		$widget['wrapper']['class'] = $widget['wrapperClass'];
	}
@endphp
@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_start'))
	<div class="jumbotron mb-2">

	  @if (isset($widget['heading']))
	  <h1 class="display-3">{!! $widget['heading'] !!}</h1>
	  @endif

	  @if (isset($widget['content']))
	  <p class="lead">{!! $widget['content'] !!}</p>
	  <hr class="my-4">
	  @endif

	  @if (isset($widget['button_link']))
	  <p class="lead">
	    <a class="btn btn-primary" href="{{ $widget['button_link'] }}" role="button">{{ $widget['button_text'] }}</a>
	  </p>
	  <hr class="my-4">
	  @endif
	</div>
@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_end'))
