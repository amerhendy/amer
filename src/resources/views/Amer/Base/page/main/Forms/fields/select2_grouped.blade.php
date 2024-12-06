<!-- select2 -->
<?php
    $current_value = old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' ));
    $field['allows_null'] = $field['allows_null'] ?? $Amer->model::isColumnNullable($field['name']);
        $related_model = $Amer->getRelationModel($field['entity']);
        $group_by_model = (new $related_model)->{$field['group_by']}()->getRelated();
        $categories = $group_by_model::with($field['group_by_relationship_back'])->get();

        if (isset($field['model'])) {
            $categorylessEntries = $related_model::doesnthave($field['group_by'])->get();
        }
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <select
    name="{{ $field['name'] }}"
    id="{{ $field['name'] }}"
        style="width: 100%"
        data-init-function="bpFieldInitSelect2GroupedElement"
        data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
        data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control select2_field'])
        >

            @if ($field['allows_null'])
                <option value="">-</option>
            @endif

            @if (isset($field['model']) && isset($field['group_by']))
                @foreach ($categories as $category)
                    <optgroup label="{{ $category->{$field['group_by_attribute']} }}">
                        @foreach ($category->{$field['group_by_relationship_back']} as $subEntry)
                            <option value="{{ $subEntry->getKey() }}"
                                @if ( ( old($field['name']) && old($field['name']) == $subEntry->getKey() ) || (isset($field['value']) && $subEntry->getKey()==$field['value']))
                                     selected
                                @endif
                            >{{ $subEntry->{$field['attribute']} }}</option>
                        @endforeach
                    </optgroup>
                @endforeach

                @if ($categorylessEntries->count())
                    <optgroup label="-">
                        @foreach ($categorylessEntries as $subEntry)

                            @if($current_value == $subEntry->getKey())
                                <option value="{{ $subEntry->getKey() }}" selected>{{ $subEntry->{$field['attribute']} }}</option>
                            @else
                                <option value="{{ $subEntry->getKey() }}">{{ $subEntry->{$field['attribute']} }}</option>
                            @endif
                        @endforeach
                    </optgroup>
                @endif
            @endif
    </select>
        @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
    @push('after_styles')
        <!-- include select2 css-->
        @loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
        @loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css')
    @endpush
    @push('after_scripts')
        <!-- include select2 js-->
        @loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
        @if (app()->getLocale() !== 'en')
        @loadScriptOnce('js/packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
        @endif
        @loadScriptOnce('js/Amer/forms/select2.js')
        <script>
            function bpFieldInitSelect2GroupedElement(element) {
                if (!element.hasClass("select2-hidden-accessible"))
                {
                    select2f=setSelect2Info($(element).attr('uniqueid'));
                    $(element).select2(select2f);
                }
            }
        </script>
        @endLoadOnce
    @endpush
