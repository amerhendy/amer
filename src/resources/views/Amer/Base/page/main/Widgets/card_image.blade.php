<!-- widgetview.card -->
@php
/**
 $widgets['before_content'][] = [
	        'type'        => 'card_image',
            'view'      =>'hover',//overlay, hover,top,bottom,start,end
            'link'      =>asset('images/logo.png'),
            'hoverlink'      =>url("amer"), //work with view:hover
            'style'         =>'light',//primary, secondary , success ,danger ,warning ,info ,light ,dark 
            'class'         =>'text-center',//text-center
            'content'       =>
                                [
                                    'title'=>'Special title treatment',
                                    'body'=>'  This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer. '
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
            'footer'=>'linked by hcww',
	];
   */
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
	$widget['wrapper']['class'] = $widget['wrapper']['class'] ?? $widget['wrapperClass'] ?? 'col-sm-6 col-md-4';
@endphp
@if(isset($widget['view']))
@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_start'))
@if(($widget['view'] == 'top') || ($widget['view'] == 'bottom'))
<div class="card {{$widget['class'] ?? ''}} {{$style}} mb-3">
  @if($widget['view'] == 'top')
  <img src="{{$widget['link']}}" class="card-img-top" alt="Wild Landscape"/>
  @endif
  <div class="card-body">
    @isset($widget['content']['title'])
    <h5 class="card-title">{{$widget['content']['title']}}</h5>
    @endisset
    @isset($widget['content']['body'])
    <p class="card-text">
    {{$widget['content']['body']}}
    </p>
    @endisset
    @isset($widget['button'])
    @foreach($widget['button'] as $btn)
    <a href="{{$btn['link']?? url('')}}" class="btn btn-{{$btn['type']?? 'primary'}}">{{$btn['text'] ?? 'submit'}}</a>
    @endforeach
    @endisset
    @isset($widget['footer'])<p class="card-text"><small class="text-muted">{{$widget['footer']}}</small></p>@endisset
  </div>
  @if($widget['view'] == 'bottom')
  <img src="{{$widget['link']}}" class="card-img-bottom" alt="Wild Landscape"/>
  @endif
</div>
@endif
@if(($widget['view'] == 'start') || ($widget['view'] == 'end'))
<div class="card mb-3 {{$widget['class'] ?? ''}} {{$style}}" style="max-width: 540px;">
  <div class="row g-0">
  @if($widget['view'] == 'start')
    <div class="col-md-4">
      <img src="{{asset('images/logo.png')}}" alt="Trendy Pants and Shoes" class="img-fluid rounded-start"/>
    </div>
  @endif
    <div class="col-md-8">
      <div class="card-body">
        @isset($widget['content']['title'])
          <h5 class="card-title">{{$widget['content']['title']}}</h5>
        @endisset
        @isset($widget['content']['body'])
        <p class="card-text">
        {{$widget['content']['body']}}
        </p>
        @endisset
        @isset($widget['button'])
        @foreach($widget['button'] as $btn)
        <a href="{{$btn['link']?? url('')}}" class="btn btn-{{$btn['type']?? 'primary'}}">{{$btn['text'] ?? 'submit'}}</a>
        @endforeach
        @endisset
        @isset($widget['footer'])<p class="card-text"><small class="text-muted">{{$widget['footer']}}</small></p>@endisset
      </div>
    </div>
    @if($widget['view'] == 'end')
    <div class="col-md-4">
      <img src="{{asset('images/logo.png')}}" alt="Trendy Pants and Shoes" class="img-fluid rounded-end"/>
    </div>
  @endif
  </div>
</div>
@endif
@if($widget['view'] == 'overlay')
<div class="card bg-dark {{$widget['class'] ?? ''}} {{$style}}">
  <img src="{{$widget['link']}}" class="card-img {{$style}}" alt="Stony Beach"/>
  <div class="card-img-overlay">
      @isset($widget['content']['title'])
        <h5 class="card-title">{{$widget['content']['title']}}</h5>
      @endisset
      @isset($widget['content']['body'])
      <p class="card-text">
      {{$widget['content']['body']}}
      </p>
      @endisset
      @isset($widget['button'])
      @foreach($widget['button'] as $btn)
      <a href="{{$btn['link']?? url('')}}" class="btn btn-{{$btn['type']?? 'primary'}}">{{$btn['text'] ?? 'submit'}}</a>
      @endforeach
      @endisset
      @isset($widget['footer'])<p class="card-text"><small class="text-muted">{{$widget['footer']}}</small></p>@endisset
  </div>
</div>
@endif
@if($widget['view'] == 'hover')
<div class="card {{$widget['class'] ?? ''}} {{$style}}">
  <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
    <img src="{{$widget['link']}}" class="img-fluid"/>
    @isset($widget['hoverlink'])
    <a href="{{$widget['hoverlink']}}" target="_blank">
    @endisset
      <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
    </a>
  </div>
  <div class="card-body">
      @isset($widget['content']['title'])
        <h5 class="card-title">{{$widget['content']['title']}}</h5>
      @endisset
      @isset($widget['content']['body'])
      <p class="card-text">
      {{$widget['content']['body']}}
      </p>
      @endisset
      @isset($widget['button'])
    @foreach($widget['button'] as $btn)
    <a href="{{$btn['link']?? url('')}}" class="btn btn-{{$btn['type']?? 'primary'}}">{{$btn['text'] ?? 'submit'}}</a>
    @endforeach
    @endisset
    @isset($widget['footer'])<p class="card-text"><small class="text-muted">{{$widget['footer']}}</small></p>@endisset
  </div>
</div>
@endif

@includeWhen(!empty($widget['wrapper']), Widgetview('inc.wrapper_end'))
@else

@endif