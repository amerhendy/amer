<!-- checkbox.blade -->
@php
  $field['value'] = old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '';
@endphp
<div class="col">
<div class="form-check form-switch">
<input type="hidden" name="{{ $field['name'] }}" value="{{ $field['value'] }}">
  <input
        data-init-function="bpFieldInitCheckbox"
        class="form-check-input"
        type="checkbox"
        id="flexSwitchCheckDefault"
        @if ((bool)$field['value'])
                 checked="checked"
          @endif
          @if (isset($field['attributes']))
              @foreach ($field['attributes'] as $attribute => $value)
    			{{ $attribute }}="{{ $value }}"
        	  @endforeach
          @endif
        >
  <label class="form-check-label" for="flexSwitchCheckDefault">{!! $field['label'] !!}</label>
</div>
</div>
      @push('after_scripts')
      @loadOnce('bpFieldInitCheckbox')
        <script>

            function bpFieldInitCheckbox(element) {
                var hidden_element = element.siblings('input[type=hidden]');
                var id = 'checkbox_'+Math.floor(Math.random() * 1000000);
                if (hidden_element.val() === '') hidden_element.val(0).trigger('change');

                // set unique IDs so that labels are correlated with inputs
                element.attr('id', id);
                element.siblings('label').attr('for', id);

                // set the default checked/unchecked state
                // if the field has been loaded with javascript
                if (hidden_element.val() != 0) {
                  element.prop('checked', 'checked');
                } else {
                  element.prop('checked', false);
                }

                hidden_element.on('AmerField:disable', function(e) {
                  element.prop('disabled', true);
                });
                hidden_element.on('AmerField:enable', function(e) {
                  element.removeAttr('disabled');
                });

                // when the checkbox is clicked
                // set the correct value on the hidden input
                element.change(function() {
                  if (element.is(":checked")) {
                    hidden_element.val(1).trigger('change');
                  } else {
                    hidden_element.val(0).trigger('change');
                  }
                })
            }
        </script>
        @endLoadOnce
    @endpush
