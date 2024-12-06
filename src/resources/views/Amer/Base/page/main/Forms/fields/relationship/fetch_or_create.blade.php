<!--fetch_or_create.blade-->
<!--  relationship  -->
<?php
    $entityWithoutAttribute = $Amer->getOnlyRelationEntity($field);
    //dd($entityWithoutAttribute);
    //$routeEntity = Str::kebab($entityWithoutAttribute);
    $routeEntity =$entityWithoutAttribute;
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    // make sure the $field['value'] takes the proper value
    // and format it to JSON, so that select2 can parse it
    $current_value = old(square_brackets_to_dots($field['name'])) ?? old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
    if (!empty($current_value) || is_int($current_value)) {
        switch (gettype($current_value)) {
            case 'array':
                $current_value = $connected_entity
                                    ->whereIn($connected_entity_key_name, $current_value)
                                    ->get()
                                    ->pluck($field['attribute'], $connected_entity_key_name)
                                    ->toArray();
                break;
            case 'object':
            if (is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model') ) {
                    $current_value = [$current_value->{$connected_entity_key_name} => $current_value->{$field['attribute']}];
                }else{
                    if(! $current_value->isEmpty())  {
                    $current_value = $current_value
                                    ->pluck($field['attribute'], $connected_entity_key_name)
                                    ->toArray();
                    }
                }
            break;
            default:
                $current_value = $connected_entity
                                ->where($connected_entity_key_name, $current_value)
                                ->get()
                                ->pluck($field['attribute'], $connected_entity_key_name)
                                ->toArray();

                break;
        }
    }
    $field['value'] = json_encode($current_value);
    $field['data_source'] = Route($field['data_source']) ?? url($Amer->route.'/fetch/'.$routeEntity);
    $field['include_all_form_fields'] = $field['include_all_form_fields'] ?? true;

    // this is the time we wait before send the query to the search endpoint, after the user as stopped typing.
    $field['delay'] = $field['delay'] ?? 500;



$activeInlineCreate = !empty($field['inline_create']) ? true : false;

if($activeInlineCreate) {
    //we check if this field is not beeing requested in some InlineCreate operation.
    //this variable is setup by InlineCreate modal when loading the fields.
    if(!isset($inlineCreate)) {
        //by default, when creating an entity we want it to be selected/added to selection.
        $field['inline_create']['force_select'] = $field['inline_create']['force_select'] ?? true;

        $field['inline_create']['modal_class'] = $field['inline_create']['modal_class'] ?? 'modal-dialog';

        //if user don't specify a different entity in inline_create we assume it's the same from $field['entity'] kebabed
        $field['inline_create']['entity'] = $field['inline_create']['entity'] ?? $routeEntity;
        //route to create a new entity
        $field['inline_create']['create_route'] = $field['inline_create']['create_route'] ?? route($field['inline_create']['entity']."-inline-create-save");
        //route to modal
        $field['inline_create']['modal_route'] = $field['inline_create']['modal_route'] ?? route($field['inline_create']['entity']."-inline-create");

        //include main form fields in the request when asking for modal data,
        //allow the developer to modify the inline create modal
        //based on some field on the main form
        $field['inline_create']['include_main_form_fields'] = $field['inline_create']['include_main_form_fields'] ?? false;

        if(!is_bool($field['inline_create']['include_main_form_fields'])) {
            if(is_array($field['inline_create']['include_main_form_fields'])) {
                $field['inline_create']['include_main_form_fields'] = json_encode($field['inline_create']['include_main_form_fields']);
            }else{
                //it is a string or treat it like
                $arrayed_field = array($field['inline_create']['include_main_form_fields']);
                $field['inline_create']['include_main_form_fields'] = json_encode($arrayed_field);
            }
        }
    }
}
?>
@include(fieldview('inc.wrapper_start'))
        <label>{!! $field['label'] !!}</label>
        @include(fieldview('inc.translatable_icon'))
        @if($activeInlineCreate)
            @include(fieldview('relationship.inline_create_button'), ['field' => $field])
        @endif
<select
        name="{{ $field['name'].($field['multiple']?'[]':'') }}"
        id="{{ $field['name']  }}"
        data-label="{!! $field['label'] !!}"
        data-active-Inline-Create="{{var_export($activeInlineCreate ?? false)}}"
        data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
        data-original-name="{{ $field['name'] }}"
        style="width: 100%"
        data-force-select="{{ var_export($field['inline_create']['force_select']) }}"
        data-init-function="bpFieldInitFetchOrCreateElement"
        data-allows-null="{{var_export($field['allows_null'])}}"
        data-dependencies="{{ isset($field['dependencies'])?json_encode(Arr::wrap($field['dependencies'])): json_encode([]) }}"
        data-model-local-key="{{$Amer->model->getKeyName()}}"
        data-placeholder="{{ $field['placeholder'] }}"
        data-data-source="{{ $field['data_source'] }}"
        data-method="{{ $field['method'] ?? 'post' }}"
        data-minimum-input-length="{{ $field['minimum_input_length'] }}"
        data-field-attribute="{{ $field['attribute'] }}"
        data-connected-entity-key-name="{{ $connected_entity_key_name }}"
        data-include-all-form-fields="{{ var_export($field['include_all_form_fields']) }}"
        data-current-value="{{ $field['value'] }}"
        data-field-ajax="{{var_export($field['ajax'])}}"
        data-inline-modal-class="{{ $field['inline_create']['modal_class'] }}"
        data-app-current-lang="{{ app()->getLocale() }}"
        data-include-main-form-fields="{{ is_bool($field['inline_create']['include_main_form_fields']) ? var_export($field['inline_create']['include_main_form_fields']) : $field['inline_create']['include_main_form_fields'] }}"
        data-ajax-delay="{{ $field['delay'] }}"
        data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
        @if($activeInlineCreate)
            @include(fieldview('relationship.field_attributes'))
        @endif
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control select2_field'])
        @if($field['multiple'])
        multiple
        @endif
        >

</select>
@if($activeInlineCreate)

@endif
 {{-- HINT --}}
 @if (isset($field['hint']))
 <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include(fieldview('inc.wrapper_end'))
@push('after_styles')
@loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
@loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap-5-theme.rtl.min.css')
@endpush
@push('after_scripts')
    @loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
    @loadScriptOnce('js/packages/select2/dist/js/i18n/'.str_replace('_', '-', app()->getLocale()).'.js')
    @loadScriptOnce('js/Amer/forms/select2_from_ajax.js')
    @loadOnce('bpFieldInitFetchOrCreateElement')
    <script>
    var emptyTranslation = '{{ trans("AMER::Base.empty_translations") }}';
    document.styleSheets[0].addRule('.select2-selection__clear::after','content:  "{{ trans('AMER:Base.clear') }}";');
    </script>
    @endLoadOnce
@endpush
<!--fetch_or_create.blade-->
