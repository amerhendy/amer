<!-- select_and_order -->
<?php
    $fieldName=$field['name'].($field['multiple']?'[]':'');
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
	$current_value = old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '';
    $entity_model = $Amer->getRelationModel($field['entity'],  - 1);
    if (is_object($current_value) && is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model') ) {
        $current_value = $current_value->getKey();
    }
    if (!isset($field['options'])) {
        $options = $field['model']::all();
        $attr=$field['attribute'];
        $opt=Arr::map($options->toArray(),function($v,$k)use($attr){
            return [
                'id'=>$v['id'],'text'=>$v[$attr]
            ];
        });
        $field['options']=$opt;
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }
?>
@php
    $values = old($field['name']) ?? $field['value'] ?? $field['default'] ?? [];
    $values = (array)$values;
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>

    <div class="row"
         data-init-function="bpFieldInitSelectAndOrderElement"
         data-all-options='@json($field['options'])'
         data-field-name="{{ $field['name'] }}">
        <div class="col-sm-6">
            <ul data-identifier="drag-destination" class="{{ $field['name'] }}_connectedSortable select_and_order_selected float-left border border-warning"></ul>
            </div>
        <div class="col-sm-6">
            <ul data-identifier="drag-source" class="{{ $field['name'] }}_connectedSortable select_and_order_all float-right border border-primary"></ul>
        </div>
            {{-- The results will be stored here --}}
            <div data-identifier="results">
                <select class="d-none"
                    name="{{ $fieldName }}"
                    id="{{ $field['name'] }}"
                    data-selected-options='@json($values)'
                    placeholder="{{ $field['placeholder'] }}"
                    multiple>
                </select>
            </div>
</div>
        @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
@push('after_scripts')
@loadScriptOnce('js/jquery/jquery-ui.min.js')
@loadScriptOnce('js/Amer/forms/select_and_order.js')
@endpush
