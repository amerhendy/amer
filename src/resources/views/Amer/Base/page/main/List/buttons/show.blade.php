<!--buttons.show.blade-->
@if ($Amer->hasAccess('show'))
	@if (!$Amer->model->translationEnabled())
	{{-- Single edit button --}}
	<a 
	href="{{Route($Amer->routelist['show']['as'],$entry->getKey())}}" 
	class="btn btn-sm btn-primary"
	data-toggle="tooltip"
	title="{{trans('AMER::actions.preview')}}"
	><i class="fa fa-eye"></i></a>
	@else
	{{-- Edit button group --}}
	<div class="btn-group">
	  <a href="{{Route($Amer->routelist['show']['as'],$entry->getKey())}}"  class="btn btn-sm btn-link pr-0"><i class="fa fa-eye"></i> {{ trans('AMER::actions.preview') }}</a>
	  <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    <span class="caret"></span>
	  </a>
	  <ul class="dropdown-menu dropdown-menu-right">
  	    <li class="dropdown-header">{{ trans('AMER::actions.preview') }}:</li>
	  	@foreach ($Amer->model->getAvailableLocales() as $key => $locale)
		  	<a class="dropdown-item" href="{{Route($Amer->route.'.show',$entry->getKey())}}?_locale={{ $key }}">{{ $locale }}</a>
	  	@endforeach
	  </ul>
	</div>

	@endif
@endif
