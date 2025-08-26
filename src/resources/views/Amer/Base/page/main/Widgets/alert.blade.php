<!-- widgetview.alert -->
@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_start'))
@php
if(!isset($widget['class'])){$widget['class']='primary';}
if(!in_array($widget['class'],['success', 'info', 'warning', 'danger', 'primary', 'secondary', 'light', 'dark'])){$widget['class']='primary';}
$class=$widget['class'];
if(!isset($widget['icon'])){$widget['icon']=false;}

/*[
	'type'          => 'alert',
	'class'			=>'primary', //success, info, warning, danger, primary, secondary, light, dark
	'close_button'  =>true,
	'heading'       =>'title',
	'content'       => 'Simple HTML content',
    'icon'          =>false, //exclamation,check,info,
]*/
@endphp
<div class="alert alert-{{ $class }} @if (isset($widget['close_button']) && $widget['close_button']) alert-dismissible fade show @endif" role="alert">
	@if (isset($widget['close_button']) && $widget['close_button'])
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button>
    <br>
	@endif
	@if (isset($widget['header']))
    @if (isset($widget['close_button']) && $widget['close_button'])
    @endif
	<h4 class="alert-heading">{!! $widget['header'] !!}</h4>

	@endif
    @if($widget['icon'] !== false)
    {!! $widget['icon'] !!}
    <div>
    @endif
	@if (isset($widget['content']))
	{!! $widget['content'] !!}
	@endif
    @if($widget['icon'] !== false)
    </div>
    @endif
    @if (isset($widget['AdditionalContent']))
    <hr>
    <p class="mb-0">{!! $widget['AdditionalContent'] !!}</p>
    @endif

</div>
@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_end'))
.alert-heading
.alert-link
.alert-dismissible
