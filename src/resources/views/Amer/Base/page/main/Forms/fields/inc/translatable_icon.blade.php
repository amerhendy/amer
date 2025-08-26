@php
    // if field name is array we check if any of the arrayed fields is translatable
    $translatable = false;
    if($Amer->model->translationEnabled()) {
        foreach((array) $field['name'] as $field_name){
            if($Amer->model->isTranslatableAttribute($field_name)) {
                $translatable = true;
            }
        }
        // if the field is a fake one (value is stored in a JSON column instead of a direct db column)
        // and that JSON column is translatable, then the field itself should be translatable
        if(isset($field['store_in']) && $Amer->model->isTranslatableAttribute($field['store_in'])) {
                $translatable = true;
        }
    }
@endphp
@if ($translatable && config('Amer.Base.show_translatable_field_icon'))
    <i class="faa fa-flag-checkered pull-{{ config('Amer.Base.translatable_field_icon_position') }}" style="margin-top: 3px;" title="This field is translatable."></i>
@endif
