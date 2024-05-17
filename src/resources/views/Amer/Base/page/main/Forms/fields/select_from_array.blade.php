<!-- select_from_array -->
<?php
if (!isset($field['options'])) {
    $entity_model = $Amer->getRelationModel($field['entity'],  - 1);
    $options = $field['model']::all();
    $attr=$field['attribute'];
    $opt=Arr::map($options->toArray(),function($v,$k)use($attr){
        return $v[$attr];
    });
    $field['options']=$opt;
} else {
    $options = $field['options'];
}
$field['allows_null'] = $field['allows_null'] ?? $Amer->model::isColumnNullable($field['name']) ?? false;
$field['value'] = old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '';
$field['multiple'] = $field['allows_multiple'] ?? $field['multiple'] ?? false;
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <select
        id="{{ $field['name'] }}"
        name="{{ $field['name'] }}@if (isset($field['allows_multiple']) && $field['allows_multiple']==true)[]@endif"
        @include(fieldview('inc.attributes'))
        @if (isset($field['allows_multiple']) && $field['allows_multiple']==true)multiple @endif
        >
        @if ($field['allows_null'])
            <option value="">-</option>
        @endif

        @if (count($field['options']))
            @foreach ($field['options'] as $key => $value)
                @if((old(square_brackets_to_dots($field['name'])) !== null && (
                        $key == old(square_brackets_to_dots($field['name'])) ||
                        (is_array(old(square_brackets_to_dots($field['name']))) &&
                        in_array($key, old(square_brackets_to_dots($field['name'])))))) ||
                        (null === old(square_brackets_to_dots($field['name'])) &&
                            ((isset($field['value']) && (
                                        $key == $field['value'] || (
                                                is_array($field['value']) &&
                                                in_array($key, $field['value'])
                                                )
                                        )) ||
                                (!isset($field['value']) && isset($field['default']) &&
                                ($key == $field['default'] || (
                                                is_array($field['default']) &&
                                                in_array($key, $field['default'])
                                            )
                                        )
                                ))
                        ))
                    <option value="{{ $key }}" selected>{{ $value }}</option>
                @else
                    <option value="{{ $key }}">{{ $value }}</option>
                @endif
            @endforeach
        @endif
    </select>
    
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include(fieldview('inc.wrapper_end'))