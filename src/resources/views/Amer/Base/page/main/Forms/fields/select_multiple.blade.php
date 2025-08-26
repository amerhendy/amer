{{-- select multiple --}}
<?php
    $fieldName=$field['name'].($field['multiple']?'[]':'');
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }
    $field['allows_null'] = $field['allows_null'] ?? true;
    $field['value'] = old_empty_or_null($field['name'], collect()) ??  $field['value'] ?? $field['default'] ?? collect();
    if (is_a($field['value'], \Illuminate\Support\Collection::class)) {
        $field['value'] = $field['value']->pluck(app($field['model'])->getKeyName())->toArray();
    }
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
    <input type="hidden" name="{{ $field['name'] }}" value="" @if(in_array('disabled', $field['attributes'] ?? [])) disabled @endif />
    <select
    	class="form-control"
        name="{{ $fieldName }}"
        id="{{ $field['name'] }}"
        placeholder="{{ $field['placeholder'] }}"
        style="width: 100%"
        @include(fieldview('inc.attributes'))
        bp-field-main-input
    	multiple>

    	@if (count($options))
    		@foreach ($options as $option)
				@if(in_array($option->getKey(), $field['value']))
					<option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
				@else
					<option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
				@endif
    		@endforeach
    	@endif

	</select>

    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
