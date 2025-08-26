<?php
?>
<!-- json.blade -->
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
<textarea name="{{$field['name']}}"></textarea>
<div
id="jsoneditor"
data-init-function="bpFieldInitjsonElement"
name="{{$field['name']}}"
style="width: 100%"></div>
@if (isset($field['hint']))
    <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
@endif
@include(fieldview('inc.wrapper_end'))

<?php
  if(isset($field['value']) && $field['value'] !== null){
      $value=$field['value'];
  }
?>
@push('after_styles')
<style>
    #jsoneditor {
      max-height: 500px;
      min-height:200px;
    }
</style>
@loadStyleOnce('js/packages/jsoneditor-develop/dist/jsoneditor.min.css')
@endpush
@push('after_scripts')
@loadScriptOnce('js/packages/jsoneditor-develop/dist/jsoneditor.min.js')
@loadOnce('bpFieldInitjsonElement')
<script>
    function bpFieldInitjsonElement(element){
        const container = element[0]
        const elementname=$(container).attr('name');
        const elementtextarea=$('textarea[name='+elementname+']');
  const options = {
    mode: 'tree',
    //modes: ['code', 'form', 'text', 'tree', 'view', 'preview'],
    onChange: function () {
        const json = editor.get();
        $(elementtextarea).val("");
        $(elementtextarea).val(JSON.stringify(json, null, 2));
    }
  };
@if(isset($value))
  const json = JSON.parse(@json($value));
  const editor = new JSONEditor(container, options, json)
@else
const editor = new JSONEditor(container, options)
@endif
    }
</script>

@endLoadOnce
@endpush
