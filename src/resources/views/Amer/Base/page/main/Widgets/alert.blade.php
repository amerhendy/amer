<!-- widgetview.alert -->
@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_start'))
@php
if(isset($widget['class'])){
	$class=$widget['class'];
}else{
	$widget['class']='alert';
}
/*[
	'type'        => 'alert',
	'class'			=>'primary', //success, info, warning, danger, primary, secondary, light, dark
	'close_button'=>true,
	'heading'       =>'title',
	'content'     => 'Simple HTML content',
]*/
@endphp
<div class="alert alert-{{ $class }}" role="alert" data-mdb-color="{{$class}}">

	@if (isset($widget['close_button']) && $widget['close_button'])	
	<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
	@endif

	@if (isset($widget['heading']))
	<h4 class="alert-heading">{!! $widget['heading'] !!}</h4>
	@endif

	@if (isset($widget['content']))
	<p>{!! $widget['content'] !!}</p>
	@endif

</div>
@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_end'))