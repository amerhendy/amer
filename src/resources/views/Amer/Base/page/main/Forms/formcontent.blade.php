@isset($fields)
<?php
$required=[];
        foreach ($fields as $key => $field) {
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
        $requiredfields={{ Illuminate\Support\Js::from($required) }};
        var $feed=`<div class="valid-feedback">Looks good!</div>`;
        $.each($requiredfields,function(k,v){
                $('[name='+v+']').attr('required','required')
                var osa=new AmerField(v);
                osa.require(true);
                if($('input[name='+v+']').length !==0){
                        var input =$('input[name='+v+']');
                        $(input).attr('required','required')
                        $($feed).insertAfter($(input))
                }
                if($('select[name='+v+']').length !==0){
                        var input =$('select[name='+v+']');
                        $(input).attr('required','required')
                }
                if($('textarea[name='+v+']').length !==0){
                        var input =$('textarea[name='+v+']');
                        $(input).attr('required','required')
                }
                if($('radio[name='+v+']').length !==0){
                        var input =$('radio[name='+v+']');
                        $(input).attr('required','required')
                }
                if($('checkbox[name='+v+']').length !==0){
                        var input =$('checkbox[name='+v+']');
                        $(input).attr('required','required')
                }
        });
        
        document.AmerField={};
        formvalidation();
        function formvalidation(){
                jQuery(document).ready(function() {
                        var inputs = document.forms["form"].getElementsByTagName("input");
                        var selects = document.forms["form"].getElementsByTagName("select");
                        var textarea =document.forms["form"].getElementsByTagName("textarea");
                        $.each(inputs,function(index,element){
                                if($(element).attr('type') == 'text'){
                                        //console.log($(element).attributes());
                                }
                        });
                        //console.log(forms);
                });
        }
        var saveActions = $('#saveActions'),
      AmerForm        = saveActions.parents('form'),
      saveActionField = $('[name="save_action"]');
      saveActions.on('click', '.dropdown-menu a', function(){
          var saveAction = $(this).data('value');
          saveActionField.val( saveAction );
          AmerForm.submit();
      });
      $(document).keydown(function(e) {
          if ((e.which == '115' || e.which == '83' ) && (e.ctrlKey || e.metaKey))
          {
              e.preventDefault();
              $("button[type=submit]").trigger('click');
              return false;
          }
          return true;
      });
      AmerForm.submit(function (event) {
        $("button[type=submit]").prop('disabled', true);
      });
      @if( $Amer->getAutoFocusOnFirstField() )
        @php
          $focusField = Arr::first($fields, function($field) {
              return isset($field['auto_focus']) && $field['auto_focus'] == true;
          });
        @endphp
        @if ($focusField)
          @php
            $focusFieldName = isset($focusField['value']) && is_iterable($focusField['value']) ? $focusField['name'] . '[]' : $focusField['name'];
          @endphp
          window.focusField = $('[name="{{ $focusFieldName }}"]').eq(0),
        @else
          var focusField = $('form').find('input, textarea, select').not('[type="hidden"]').eq(0),
        @endif
        fieldOffset = focusField.offset().top,
        scrollTolerance = $(window).height() / 2;

        focusField.trigger('focus');

        if( fieldOffset > scrollTolerance ){
            $('html, body').animate({scrollTop: (fieldOffset - 30)});
        }
      @endif
      @if ($Amer->inlineErrorsEnabled() && $errors->any())
        window.errors = {!! json_encode($errors->messages()) !!};
        $.each(errors, function(property, messages){
                var normalizedProperty = property.split('.').map(function(item, index){
                    return index === 0 ? item : '['+item+']';
                }).join('');
                var field = $('[name="' + normalizedProperty + '[]"]').length ?
                        $('[name="' + normalizedProperty + '[]"]') :
                        $('[name="' + normalizedProperty + '"]'),
                        container = field.parents('.form-group');
                container.addClass('text-danger');
                container.children('input, textarea, select').addClass('is-invalid');
                $.each(messages, function(key, msg){
                        var row = $('<div class="invalid-feedback d-block">' + msg + '</div>');
                        row.appendTo(container);
                        @if ($Amer->tabsEnabled())
                                var tab_id = $(container).closest('[role="tabpanel"]').attr('id');
                                $("#form_tabs [aria-controls="+tab_id+"]").addClass('text-danger');
                        @endif
            });
        });

      @endif
        $("a[data-toggle='tab']").click(function(){
          currentTabName = $(this).attr('tab_name');
          $("input[name='current_tab']").val(currentTabName);
      });
      if (window.location.hash) {
          $("input[name='current_tab']").val(window.location.hash.substr(1));
      }
      
</script>
@endpush