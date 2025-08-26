<!--buttons.reorder.blade-->
@if ($Amer->get('reorder.enabled') && $Amer->hasAccess('reorder'))
  <a href="{{ url($Amer->route.'/reorder') }}" class="btn btn-outline-primary" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-arrows"></i> {{ trans('AMER::actions.reorder') }} {{ $Amer->entity_name_plural }}</span></a>
@endif
