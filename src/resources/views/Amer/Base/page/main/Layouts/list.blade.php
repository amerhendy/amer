@extends(Baseview('app'))
@php
$route=$Amer->getRoute();
$SingularPageTitle=$Amer->getSubheading()?? $Amer->entity_name;
$PluralPageTitle=$Amer->getHeading() ?? $Amer->entity_name_plural;
if(!isset($breadcrumbs)){
        $breadcrumbs=[];
        $breadcrumbs[trans("AMER::crud.admin")]=Route('Admin');
        $breadcrumbs[$PluralPageTitle]=false;
    }
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
$apiNameFN='api.'.$route.'Lists';
$DefaultPageLength=$Amer->getDefaultPageLength() ?? $settings['list.defaultPageLength'] ?? 10;
//dd($Amer->getrequest()->getpathInfo());
@endphp
@section('header')
  <div class="container-fluid">
    <h2>
      <span class="text-capitalize">{!! $PluralPageTitle !!}</span>
    </h2>
  </div>
@endsection
@section('content')
<div class="row">
  <div class="{{$Amer->getListContentClass()}}">
    <div class="row mb-0">
      <div class="col-sm rounded shadow">
        @if ($Amer->buttons()->where('stack', 'top')->count())
          <div class="d-print-none {{ $Amer->hasAccess('create')?'with-border':'' }}">@include(listview('DataTables.button_stack'), ['stack' => 'top'])</div>
        @endif
        <div class="col-sm-6">
              <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none"></div>
        </div>
        <div class="col-sm-12">
          <table
           id="AmerTable" 
           class="table table-striped table-striped-columns table-hover table-sm display table-bordered shadow rounded compact hover row-border cell-border nowrap" 
          data-responsive-table="{{ (int) $Amer->getOperationSetting('responsiveTable') }}"
          data-has-details-row="{{ (int) $Amer->getOperationSetting('detailsRow') }}"
          data-has-bulk-actions="{{ (int) $Amer->getOperationSetting('bulkActions') }}"
          data-has-line-buttons-as-dropdown="{{ (int) $Amer->getOperationSetting('lineButtonsAsDropdown') }}">
            <thead class="table align-middle table-dark text-right">
              @foreach($Amer->columns() as $column)
              <th 
              class="text-center"
              scope="col" 
              data-orderable="false"
              data-priority="{{ $column['priority'] }}"
              data-column-name  ="{{ $column['name'] }}"
              data-column-name="{{ $column['name'] }}"
                @if(isset($column['exportOnlyField']) && $column['exportOnlyField'] === true)
                      data-visible="false"
                      data-visible-in-table="false"
                      data-can-be-visible-in-table="false"
                      data-visible-in-modal="false"
                      data-visible-in-export="true"
                      data-force-export="true"
                    @else
                      data-visible-in-table="{{var_export($column['visibleInTable'] ?? false)}}"
                      data-visible="{{var_export($column['visibleInTable'] ?? true)}}"
                      data-can-be-visible-in-table="true"
                      data-visible-in-modal="{{var_export($column['visibleInModal'] ?? true)}}"
                      @if(isset($column['visibleInExport']))
                         @if($column['visibleInExport'] === false)
                           data-visible-in-export="false"
                           data-force-export="false"
                         @else
                           data-visible-in-export="true"
                           data-force-export="true"
                         @endif
                       @else
                         data-visible-in-export="true"
                         data-force-export="false"
                       @endif
                    @endif >
                    @if($loop->first && $Amer->getOperationSetting('bulkActions'))
                      {!! View::make(listview('columns.inc.bulk_actions_checkbox'))->render() !!}
                    @endif
                    {!! $column['label'] !!}
              </th>
              @endforeach
              <th 
              scope="col" 
              data-orderable="false"
              >{{trans('AMER::actions.actions')}}</th>
              @php
              //dd($column);
              @endphp
            </thead>
            <tbody class="table-group-divider">
            </tbody>
            <tfoot>
              <tr>
              @foreach ($Amer->columns() as $column)
                  <th class="text-center">
                    {{-- Bulk checkbox --}}
                    @if($loop->first && $Amer->getOperationSetting('bulkActions'))
                    {!! View::make(listview('columns.inc.bulk_actions_checkbox'))->render() !!}
                    @endif
                    {!! $column['label'] !!}
                  </th>
                @endforeach
                @if ( $Amer->buttons()->where('stack', 'line')->count() )
                  <th>{{ trans('AMER::actions.actions') }}</th>
                @endif
                @if($Amer->get('list.export_buttons'))
                  @include(listview('buttons.export_buttons'))
                @endif
              </tr>
            </tfoot>
          </table>
          <div class="col-sm-6">
          @if ($Amer->buttons()->where('stack', 'bottom')->count())
          <div class="d-print-none">@include(listview('DataTables.button_stack'), ['stack' => 'bottom'])</div>
        @endif
              <div id="datatable_info_stack" class="mt-sm-0 mt-2 d-print-none"></div>
        </div>
        </div>
      </div>        
    </div>
  </div>
</div>

<div class="modal fade" id="jsonModal" tabindex="-1" role="dialog" aria-labelledby="jsonModalLabel" aria-hidden="true" data-bs-theme="dark">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
      <pre id="json-renderer"></pre>
      </div>
    </div>
  </div>
</div>
@php 
//dd($settings['list.buttons']);
@endphp
@endsection

@push('after_scripts')
  @loadScriptOnce("js/packages/DataTables/datatables.min.js")
  @loadOnce('test_table')
    @include(listview('DataTables.datatables'))
    <script>
    function readmore(e,type){
    mainobject=$(e).parent();
    td=$(mainobject).parent()
    tdchilds=$(td).children();
    for(i=0;i<tdchilds.length;i++){
        if($(tdchilds[i]).attr('id') == type){
            targetdiv=$(tdchilds[i]);
        }
    }
    $(targetdiv).css('display','block')
    $(mainobject).css('display','none')
}
</script>
  @endLoadOnce
@endpush

@push('after_styles')
  @loadStyleOnce("js/packages/DataTables/datatables.min.css")
@endpush
      <?php
$cols=Arr::map($Amer->columns(),function($v,$k){
    return $v['type'];
});
?>
@if(in_array('json',$cols))
  @push('after_scripts')
<!--list.blade-->
    @loadScriptOnce("js/packages/json-viewer/jquery.json-viewer.js")
    @loadStyleOnce("js/packages/json-viewer/jquery.json-viewer.css")
    <script>
      function viewjson(e){
        $('#jsonModal').modal('show')
        var elem=$(e);
        var eleid=$(elem).data('id');
        var jsoncodeing=JSON.parse(window["json_code_"+eleid]);
        $('#json-renderer').jsonViewer(jsoncodeing, {collapsed: true, withQuotes: false, withLinks: false});
      }
    </script>
<!--list.blade-->
@endpush
@endif