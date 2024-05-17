<!-- widgetview.progress -->
@php
/**
 $widgets['before_content'][] = [
	        'type'        => 'progress',
            'style'         =>'primary',//primary, secondary , success ,danger ,warning ,info ,light ,dark 
            'header'=>'The Show Header',
            'hint'=>'hint',
            'footer_link'=>'footer_link',
            'footer_text'=>'footer_text',
            'content'=>[
                [
                            'min'=>0,
                            'max'=>100,
                            'val'=>10,
                            'description'=>'description',
                        ],
                        [
                            'min'=>0,
                            'max'=>100,
                            'val'=>10,
                            'description'=>'description',
                        ],
                ],
            'value'         =>50,
            'description'       =>'description',
            'progress'  =>50,
            
	];
   */
  $widget['wrapper']['class'] = $widget['wrapper']['class'] ?? $widget['wrapperClass'] ?? 'col-sm-6 col-lg-3';
  $style='';$btncolor='';$bg='';
	if(isset($widget['style'])){
		if(($widget['style']) == 'primary'){$style='text-white bg-primary'; $bg='bg-success';$btncolor='btn-danger';}
		if(($widget['style']) == 'secondary'){$style='text-white bg-secondary'; $bg='bg-primary';$btncolor='btn-light';}
		if(($widget['style']) == 'success'){$style='text-white bg-success'; $bg='bg-primary';$btncolor='btn-light';}
		if(($widget['style']) == 'danger'){$style='text-white bg-danger'; $bg='bg-info';$btncolor='btn-light';}
		if(($widget['style']) == 'warning'){$style='bg-warning'; $bg='bg-info';$btncolor='btn-light';}
		if(($widget['style']) == 'info'){$style='text-white bg-info'; $bg='bg-success';$btncolor='btn-light';}
		if(($widget['style']) == 'light'){$style='text-dark bg-light'; $bg='bg-info';$btncolor='btn-success';}
		if(($widget['style']) == 'dark'){$style='text-white bg-dark'; $bg='bg-info';$btncolor='btn-light';}
	}

@endphp

@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_start'))
<div class="card {{$widget['class'] ?? ''}} {{$style}}  mb-4">
        @isset($widget['header'])
                <div class="card-header">
					<b>{{$widget['header']}}</b>
                </div>
				@endisset
        <div class="card-body">
          @isset($widget['content'])
            @foreach($widget['content'] as $item)
            {{$item['description']}}
            <div class="progress mb-1" style="height: 20px;">
                <div
                  class="progress-bar progress-bar-animated {{$bg}}"
                  role="progressbar"
                  aria-valuenow="{{$item['val']}}"
                  aria-valuemin="{{$item['min']}}"
                  aria-valuemax="{{$item['max']}}"
                  style="width: {{$item['val']}}%;"
                >{{$item['val']}}</div>
              </div>
            @endforeach
          @endisset
        </div>
        @if((isset($widget['footer_text'])) OR (isset($widget['footer_link'])))
                <div class="card-footer">
                @if(isset($widget['footer_link']))
                <a class="btn {{$btncolor}} border" href="{{ $widget['footer_link'] ?? '#' }}"><span class="small font-weight-bold">{{ $widget['footer_text'] ?? 'View more' }}</span><i class="la la-angle-right"></i></a>
                @else
                {{$widget['footer_text']}}
                @endif
                </div>
				@endisset
</div>
  @includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_end'))