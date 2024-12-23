<!-- select2_nested -->

{{-- Thanks to Erwan Pianezza - https://github.com/breizhwave--}}

{{-- This field assumes you have a nested set Eloquent model, using: --}}
{{-- 1. children() as a properly defined relationship --}}
{{-- 2. depth, lft attributes --}}

@php
    $current_value = old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' ));

    if (!function_exists('echoSelect2NestedEntry')) {
        function echoSelect2NestedEntry($entry, $field, $current_value) {
            if ($current_value == $entry->getKey()) {
                $selected = ' selected ';
            } else {
                $selected = '';
            }
            echo '<option value="'.$entry->getKey().'"'.$selected.'>';
            echo str_repeat("-", (int)$entry->depth > 1 ? (int)$entry->depth - 1 : 0).' '.$entry->{$field['attribute']};
            echo "</option>";
        }
    }

    if (!function_exists('echoSelect2NestedChildren')) {
        function echoSelect2NestedChildren($entity, $field, $current_value)
        {
         foreach ($entity->children()->get() as $entry)
            {
                echoSelect2NestedEntry($entry, $field, $current_value);
                echoSelect2NestedChildren($entry, $field, $current_value);
            }
        }
    }

    $entity_model = $Amer->getRelationModel($field['entity'], -1);
    $field['allows_null'] = $field['allows_null'] ?? $entity_model::isColumnNullable($field['name']);
@endphp

    <select
        name="{{ $field['name'] }}"
        id="{{ $field['name'] }}"
        style="width: 100%"
        data-init-function="bpFieldInitSelect2NestedElement"
        data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
        data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control select2_field'])
        >

        @if ($field['allows_null'])
            <option value="">-</option>
        @endif

        @if (isset($field['model']))
            @php
                $obj = new $field['model'];
                $first_level_items = $obj->where('depth', '1')->orWhere('depth', null)->orderBy('lft', 'ASC')->get();
            @endphp

            @foreach ($first_level_items as $connected_entity_entry)
                @php
                    echoSelect2NestedEntry($connected_entity_entry, $field, $current_value);
                    echoSelect2NestedChildren($connected_entity_entry, $field, $current_value);
                @endphp
            @endforeach
        @endif
    </select>

    @push('after_styles')
    @loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
    @loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css')
    @endpush
    @push('after_scripts')
    @loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
    @if (app()->getLocale() !== 'en')
    @loadScriptOnce('js/packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif
    @loadScriptOnce('js/Amer/forms/select2.js');
    @loadOnce('bpFieldInitSelect2NestedElement')
        <script>
            function bpFieldInitSelect2NestedElement(element) {
                if (!element.hasClass("select2-hidden-accessible"))
                {
                    select2f=setSelect2Info($(element).attr('uniqueid'));
                    $(element).select2(select2f);
                }
            }
        </script>
        @endLoadOnce
    @endpush
