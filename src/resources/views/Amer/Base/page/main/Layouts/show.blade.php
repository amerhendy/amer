@extends(Baseview('app'))
@php
$route=$Amer->getRoute();
$SingularPageTitle=$Amer->getSubheading()?? $Amer->entity_name;
$PluralPageTitle=$Amer->getHeading() ?? $Amer->entity_name_plural;
if(!isset($breadcrumbs)){
        $breadcrumbs=[];
        $breadcrumbs[trans("AMER::auth.admin")]=Route('Admin');
        //$breadcrumbs[$PluralPageTitle]=false;
        $breadcrumbs[$PluralPageTitle]=url($Amer->route);
        $breadcrumbs[trans('AMER::actions.preview')] = false;
    }
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp
@section('content')
<div class="row">
  <div class="{{ $Amer->getShowContentClass() }}">
    <div class="">
      <div class="card no-padding border-light">
      <div class="card-header">
        <h2>
          <span class="text-capitalize">{!! $PluralPageTitle !!}</span>
        </h2>
      </div>
      <div class="card-body">
        @foreach($Amer->columns() as $column)
        <div class="row">
          <div class="col-sm-3 border border-light">{!! $column['label'] !!}</div>
          <div class="col-sm border border-light">
            
            @if(!isset($column['type']))
              @include(listview('columns.text'))
            @else
              @if(!view()->exists(listview('columns.'.$column['type'])))
                @include(listview('columns.text'))
              @else
                @include(listview('columns.'.$column['type']))
              @endif
            @endif
          </div>
        </div>
        
        @endforeach
      </div>
        
        <div class="card-footer text-body-secondary">
        @if ($Amer->buttons()->where('stack', 'line')->count())
          @include(listview('DataTables.button_stack'), ['stack' => 'line'])
        @endif
        </div>

      </div>
    </div>
  </div>
</div>
@endsection