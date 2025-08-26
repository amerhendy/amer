@isset($fields)
<?php
$required=[];
        foreach ($fields as $key => $field) {
            if(\Str::contains($field['type'], ['select2'])){
                //dd($field);
            }
                if(!isset($field['parentFieldName']) || !$field['parentFieldName']) {
                        if(!is_array($field['name'])){
                                $fieldName = $Amer->holdsMultipleInputs($field['name']) ? explode(',', $field['name']) : [$field['name']];
                        }else{
                                $fieldName =$field['name'];
                        }

                        foreach($fieldName as $inputName) {

                                if(isset($action) && $Amer->isRequired($inputName)){
                                        $required[]=$inputName;
                                }
                        }
                }
        }
?>

@if($Amer->tabsEnabled() && count($Amer->getTabs()))
        @include(fieldview('inc.tabed'))
        <input type="hidden" name="current_tab" value="{{ Str::slug($Amer->getTabs()[0]) }}" />
@else
        @include(fieldview('inc.show_fields'))
@endif
@endisset
@push('after_scripts')
<style>.form-group.required div .form-label:after
{
      content:"*";
      color:red;
}
.select2-selection.required {
   background-color: yellow !important;
}
</style>

<script>
    window.Amer.AmerField={};
    window.Amer.requiredfields={{ Illuminate\Support\Js::from($required) }};
    window.Amer.fieldfeed=`<div class="valid-feedback">Looks good!</div>`;
    window.Amer.getAutoFocusOnFirstField="{{$Amer->getAutoFocusOnFirstField() ?? 'false'}}";
    window.Amer.inlineErrorsEnabled="{{$Amer->inlineErrorsEnabled() ?? 'false'}}";
    @if ($Amer->inlineErrorsEnabled() && $errors->any())
    window.Amer.FormErrors="{{Illuminate\Support\Js::from($required)}}"
    window.Amer.errors = {!! json_encode($errors->messages()) !!};
    window.Amer.tabsEnabled = "{{$Amer->tabsEnabled() ?? 'false'}}"
    @else
    window.Amer.FormErrors=false;
    @endif
      @if( $Amer->getAutoFocusOnFirstField() )
        @php
          $focusField = Arr::first($fields, function($field) {
              return isset($field['auto_focus']) && $field['auto_focus'] == true;
          });

        if ($focusField){
                $focusFieldName = isset($focusField['value']) && is_iterable($focusField['value']) ? $focusField['name'] . '[]' : $focusField['name'];
        }else{
                $focusFieldName = false;
        }
        @endphp
        window.Amer.focusField='{{$focusFieldName ?? false}}';
      @endif

</script>
@endpush
