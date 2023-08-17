<!-- widgetview.div -->
@php
/**
 [
            'type'=>'div',
            'class'=>'row',//row, column
            'content'=>[[
                        'type'        => 'card',
                        'close_button'=>true,
                        'heading'       =>'title',
                        'content'     => [
                            'header'=>'title',
                            'body'=>'body',
                            'footer'=>[
                                'type'=>'link',//text,link
                                'text'=>'aaa',
                                'link'=>url(""),
                                'class'=>'primary'
                                ],
                        ],
                    ],[
                        'type'        => 'card',
                        'close_button'=>true,
                        'heading'       =>'title',
                        'content'     => [
                            'header'=>'title',
                            'body'=>'body',
                            'footer'=>[
                                'type'=>'link',//text,link
                                'text'=>'aaa',
                                'link'=>url(""),
                                'class'=>'primary'
                                ],
                        ],
                    ],[
                        'type'        => 'card',
                        'close_button'=>true,
                        'heading'       =>'title',
                        'content'     => [
                            'header'=>'title',
                            'body'=>'body',
                            'footer'=>[
                                'type'=>'link',//text,link
                                'text'=>'aaa',
                                'link'=>url(""),
                                'class'=>'primary'
                                ],
                        ],
                    ]]
 */
$widget['class'] = $widget['class'] ?? 'row' ?? $widget['class'];
@endphp
@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_start'))
<div 
	@if (count($widget) > 2)
	    @foreach ($widget as $attribute => $value)
	        @if (is_string($attribute) && $attribute!='content' && $attribute!='type')
	            {{ $attribute }}="{{ $value }}"
	        @endif
	    @endforeach
	@endif
	>

	@if (isset($widget['content']))
		@include('Amer::Base.inc.widgets', [ 'widgets' => $widget['content'] ])
	@endif

</div>

@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_end'))