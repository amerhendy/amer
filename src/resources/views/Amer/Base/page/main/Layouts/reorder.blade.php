<?php
    $route=$Amer->getRoute();
    $columns = $Amer->getOperationSetting('reorderColumnNames');
    $all_entries = collect($entries->all())->sortBy($columns['lft'])->keyBy($Amer->getModel()->getKeyName());
    $root_entries = $all_entries->filter(function ($item) use ($columns) {return $item->{$columns['parent_id']} == 0;});
    $idParents=[];
    foreach ($all_entries as $key => $value) {
        $idParents[$value->getKey()]=$value->{$columns['parent_id']};
    }
    $allaccess=\Arr::where($Amer->settings(), function($v,$k){
        return \Str::contains($k, 'access') && $v === true;
    });
    foreach ($allaccess as $key => $value) {
        $newKey=\Str::before($key,'.');
        $allaccess[$newKey]=$value;
        unset($allaccess[$key]);
    }
?>
@extends(Baseview('blank'))
@push('meta')
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
@endpush
@push('after_styles')
@loadStyleOnce('css/bootstrap/bootstrap.min.css')
        @loadStyleOnce('css/bootstrap/bootstrap.rtl.min.css')
        @loadStyleOnce('css/bootstrap/bootstrap-grid.min.css')
        @loadStyleOnce('css/bootstrap/bootstrap-grid.rtl.min.css')
        @loadStyleOnce('css/bootstrap/bootstrap-reboot.rtl.min.css')
        @loadStyleOnce('css/bootstrap/bootstrap-utilities.rtl.min.css')
        @loadStyleOnce('css/awesom/all.min.css')
        @loadStyleOnce('js/packages/jquery-ui-1.14.0.custom/jquery-ui.structure.min.css')
        @loadStyleOnce('js/packages/jquery-ui-1.14.0.custom/jquery-ui.theme.min.css')
        @loadStyleOnce('js/packages/nestedSortable/CustomNestedSortable.css')
        @loadStyleOnce('js/packages/aos/aos.css')
  <style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
  #sortable li span { position: absolute; margin-left: -1.3em; }
  </style>

@push('after_scripts')
    @loadScriptOnce('js/jquery/jquery-3.6.0.min.js')
    @loadScriptOnce('js/jquery/jquery-ui.min.js')
    @loadScriptOnce('js/packages/aos/aos.js')
    @loadScriptOnce('js/packages/noty/noty.min.js')
    @loadScriptOnce('js/website.js')
    @loadScriptOnce('js/Amer/apiRequest.js')
    @loadScriptOnce('js/packages/nestedSortable/jquery.mjs.nestedSortable2.js')
    <script type="text/javascript">
        window.Amer.ReOrder={};
        window.Amer.ReOrder.MainId="amer-operation-reorder";
        window.Amer.ReOrder.entry={{ Illuminate\Support\Js::from($all_entries) }};
        window.Amer.ReOrder.root={{ Illuminate\Support\Js::from($root_entries) }};
        window.Amer.ReOrder.idParents={{ Illuminate\Support\Js::from($idParents) }};
        window.Amer.ReOrder.label="{{$Amer->get('reorder.label')}}";
        window.Amer.ReOrder.previousUrl="{{ $Amer->hasAccess('list') ? url($Amer->route) : url()->previous() }}"
        window.Amer.ReOrder.Access="";
        window.Amer.ReOrder.getReorderContentClass="{{ $Amer->getReorderContentClass() }}";
        window.Amer.ReOrder.isRtl = Boolean("{{ (config('Amer.Amer.html_direction') === 'rtl') ? true : false }}");
        window.Amer.allaccess={{ Illuminate\Support\Js::from($allaccess) }};
        window.Amer.ReOrder.key="{{$entries[0]->getKeyName()}}";
        window.Amer.ReOrder.reorderColumnNames={{ Illuminate\Support\Js::from($Amer->getOperationSetting('reorderColumnNames'))}};
        window.Amer.ReOrder.max_level="{{ $Amer->get('reorder.max_level') ?? 3 }}";
        window.Amer.ReOrder.RequestPath="{{ url(Request::path()) }}";
    </script>
  @loadScriptOnce('js/Amer/reorder.js')
@endpush
@section('content')
<div class="row mt-4" bp-section="amer-operation-reorder" id="amer-operation-reorder"></div>
@endsection
