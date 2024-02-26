@if (isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs))
	<nav aria-label="breadcrumb" class="d-none d-lg-block menu-area">
	  <ol class="breadcrumb bg-transparent p-0 justify-content-start">
	  <li class="breadcrumb-item text-capitalize"><a href="{{url('')}}"><div style="height: 30px; width: 30px; background-image:url('{{asset ('images/nsscww.gif')}}');background-repeat: no-repeat; background-size: contain; "></div></a></li>
	  	@foreach ($breadcrumbs as $label => $link)
	  		@if ($link)
			    <li class="breadcrumb-item text-capitalize"><a href="{{ $link }}">{{ $label }}</a></li>
	  		@else
			    <li class="breadcrumb-item text-capitalize active" aria-current="page">{{ $label }}</li>
	  		@endif
	  	@endforeach
	  </ol>
	</nav>
@endif