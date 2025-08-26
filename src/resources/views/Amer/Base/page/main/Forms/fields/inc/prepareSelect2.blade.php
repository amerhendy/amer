<?php
if (! function_exists('getselect2DataAttr')) {
function getselect2DataAttr($field){
    $elementDataAttr=[];
    $elementDataAttr['name']=$field['name'];
    $elementDataAttr['id']=$field['id'];
    $elementDataAttr['style']=$field['style'];
    $elementDataAttr['data-init-function']=$field['datainitfunction'];

    switch ($field['type']) {
        case 'select2_from_ajax_multiple':
            $elementDataAttr['data-model']                      =$field['modelName'];
            $elementDataAttr['data-dependencies']               =$field['dependencies'];
            $elementDataAttr['data-placeholder']                =$field['placeholder'];
            $elementDataAttr['data-minimum-input-length']       =$field['minimum_input_length'];
            $elementDataAttr['data-data-source']                =$field['data_source'];
            $elementDataAttr['data-rel-ty']                     =$field['releationType'] ?? '';
            $elementDataAttr['data-method']                     =$field['method'] ?? 'POST';
            $elementDataAttr['data-field-attribute']            =json_encode($field['attribute']);
            $elementDataAttr['data-connected-entity-key-name']  =$field['connected_entity_key_name'];
            $elementDataAttr['data-include-all-form-fields']    =$field['include_all_form_fields'];
            $elementDataAttr['data-ajax-delay']                 =$field['delay'];
            break;

        default:

            break;
    }
    $elementDataAttr['data-arrayview']                          =json_encode($field["array_view"] ?? []);
    $elementDataAttr['data-language']                           =$field['language'];
    return $elementDataAttr;
}}
    $elementDataAttr=[];
    $fieldName=$field['name'].($field['multiple']?'[]':'');
    $field['id']=$field['name'];
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }

    $bpFieldInitElement=[
        'select2_from_ajax_multiple'=>'bpFieldInitSelect2FromAjaxMultipleElement',
        'select2'=>'bpFieldInitSelect2Element',
        'select2_from_ajax'=>'bpFieldInitSelect2FromAjaxss',
        'select2_from_ajax_multipleJSON'=>'bpFieldInitSelect2FromAjaxMultipleElement',
        'select2_from_array'=>'bpFieldInitSelect2FromArrayElement',
        'select2_grouped'=>'bpFieldInitSelect2GroupedElement',
        'select2_multiple'=>'bpFieldInitSelect2MultipleElement',
        'select2_nested'=>'bpFieldInitSelect2NestedElement',
    ];
    $fieldType=$field['type'];
    $field['datainitfunction']=Arr::get($bpFieldInitElement,$fieldType);
    $field['style']="width: 100%";
    $field['readonly']=$field['readonly'] ?? 'false';
    $field['allows_null'] = $field['allows_null'] ?? $Amer->model::isColumnNullable($field['name']);
    $field['minimum_input_length']=$field['minimum_input_length'] ?? 0 ;
    $field['language']=str_replace('_', '-', app()->getLocale());
    $field['delay'] = $field['delay'] ?? 500;
    switch ($fieldType) {
        case 'relationship':
            $field['inlineCreate']=var_export($inlineCreate ?? false);

            $activeInlineCreate = !empty($field['inline_create']) ? true : false;
            if($activeInlineCreate) {
                //we check if this field is not beeing requested in some InlineCreate operation.
                //this variable is setup by InlineCreate modal when loading the fields.
                if(!isset($inlineCreate)) {
                    if(!is_array($field['inline_create'])){
                        $field['inline_create']=[];
                    }
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
            $elementDataAttr['data-field-is-inline']=$field['inlineCreate'];
            $elementDataAttr['data-force-select']=var_export($field['inline_create']['force_select']);
            $elementDataAttr['data-active-Inline-Create']=var_export($activeInlineCreate ?? false);
            $elementDataAttr['data-inline-modal-class']=$field['inline_create']['modal_class'];
            $elementDataAttr['data-include-main-form-fields']=is_bool($field['inline_create']['include_main_form_fields']) ? var_export($field['inline_create']['include_main_form_fields']) : $field['inline_create']['include_main_form_fields'];
            $elementDataAttr['data-inline-create-route']=$field['inline_create']['create_route'] ?? false;
            $elementDataAttr['data-inline-modal-route']=$field['inline_create']['modal_route'] ?? false;
            $elementDataAttr['data-field-related-name']=$field['inline_create']['entity'];
            $elementDataAttr['data-inline-create-button']=$field['inline_create']['entity']."-inline-create-".$field['name'];
            $elementDataAttr['data-inline-allow-create']=var_export($activeInlineCreate);
            break;

        default:
            # code...
            break;
    }
    if(isset($field['attribute'])){
        if(!is_array($field['attribute'])){
            $field['attribute']=[$field['attribute']];
        }
    }
    if(isset($field['data_source'])){
        $field['data_source']=Route($field['data_source']);
    }

    $field['dependencies']=isset($field['dependencies'])?json_encode(Arr::wrap($field['dependencies'])): json_encode([]);
    if(!isset($field['method'])){
        $field['method']='POST';
    }
    $field['include_all_form_fields']=$include_all_form_fields=isset($field['include_all_form_fields']) ? ($field['include_all_form_fields'] ? 'true' : 'false') : 'false';
    /////////////////get model //////////////////////////
    if(isset($field['model'])){
        $model=explode('\\',$field['model']);
        $model=$field['modelName']=$model[count($model)-1];
        $connected_entity = new $field['model'];
        $connected_entity_key_name = $connected_entity->getKeyName();
        $field['connected_entity_key_name']=$connected_entity_key_name;
        if (!isset($field['options'])) {
            $field['options'] = $field['model']::all();
        } else {
            $field['options'] = call_user_func($field['options'], $field['model']::query());
        }
        $options_ids_array = $field['options']->pluck($connected_entity->getKeyName())->toArray();
        $old_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
    }
    $fieldDataAttr=getselect2DataAttr($field);
?>
@foreach($fieldDataAttr as $k=>$v)
    {{$k}}='{{$v}}'
@endforeach
