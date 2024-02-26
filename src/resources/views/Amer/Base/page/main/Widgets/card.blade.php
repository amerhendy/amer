<!-- widgetview.card -->
@php
/*
$widgets['before_content'][] = [
	        'type'        => 'card',
            'style'         =>'secondary',//primary, secondary , success ,danger ,warning ,info ,light ,dark 
            'class'         =>'text-center',//text-center
            'header'       =>'header text',
            'content'       =>
                                [
                                    'title'=>'Special title treatment',
                                    'body'=>' With supporting text below as a natural lead-in to additional content. '
                                    ],
            'button'        =>[[
                                'type'=>'primary',
                                'text'=>'press here',
                                'link'=>url('amer')
                                ],[
                                'type'=>'primary',
                                'text'=>'press here',
                                'link'=>url('amer')
                                ]],
            'footer'=>'sssss',
	];
 */
	$widget['wrapper']['class'] = $widget['wrapper']['class'] ?? $widget['wrapperClass'] ?? 'col-sm-6 col-md-4';
	$style='';
	if(isset($widget['style'])){
		if(($widget['style']) == 'primary'){$style='text-white bg-primary';}
		if(($widget['style']) == 'secondary'){$style='text-white bg-secondary';}
		if(($widget['style']) == 'success'){$style='text-white bg-success';}
		if(($widget['style']) == 'danger'){$style='text-white bg-danger';}
		if(($widget['style']) == 'warning'){$style='bg-warning';}
		if(($widget['style']) == 'info'){$style='text-white bg-info';}
		if(($widget['style']) == 'light'){$style='text-dark bg-light';}
		if(($widget['style']) == 'dark'){$style='text-white bg-Dark';}
	}
@endphp
	@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_start'))
	<div class="card {{$widget['class'] ?? ''}} {{$style}}  mb-4">
				@isset($widget['header'])
                <div class="card-header">
					<b>{{$widget['header']}}</b>
                </div>
				@endisset
				@if (isset($widget['content']))
                <div class="card-body">
					@if (isset($widget['content']['title']))
                  		<h5 class="card-title">{{$widget['content']['title']}}</h5>
					@endif
					@if (isset($widget['content']['body']))
						<p class="card-text">
							{{$widget['content']['body']}}
						</p>
				  	@endif
				@isset($widget['button'])
				@foreach($widget['button'] as $btn)
				<a href="{{$btn['link']?? url('')}}" class="btn btn-{{$btn['type']?? 'primary'}}">{{$btn['text'] ?? 'submit'}}</a>
				@endforeach
				@endisset
                </div>
				@endif
				@isset($widget['footer'])
                <div class="card-footer bg-white text-muted">
					{{$widget['footer']}}
                </div>
				@endisset
              </div>
	@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_end'))


