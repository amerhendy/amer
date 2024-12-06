{{-- radio --}}
<!-- radio.balde -->
@php
    $optionValue = old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '';

    // check if attribute is casted, if it is, we get back un-casted values
    if(Arr::get($Amer->model->getCasts(), $field['name']) === 'boolean') {
        $optionValue = (int) $optionValue;
    }

    // if the class isn't overwritten, use 'radio'
    if (!isset($field['attributes']['class'])) {
        $field['attributes']['class'] = 'radio';
    }

    $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
    $field['wrapper']['data-init-function'] = $field['wrapper']['data-init-function'] ?? 'bpFieldInitRadioElement';
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <input type="hidden" value="{{ $optionValue }}" name="{{$field['name']}}" id="{{$field['name']}}" />

    @if( isset($field['options']) && $field['options'] = (array)$field['options'] )

        @foreach ($field['options'] as $value => $label )

            <div class="form-check {{ isset($field['inline']) && $field['inline'] ? 'form-check-inline' : '' }}">
                <input  type="radio"
                        class="form-check-input"
                        value="{{$value}}"
                        name="{{$field['name']}}"
                        @include(fieldview('inc.attributes'))
                        >
                <label class="{{ isset($field['inline']) && $field['inline'] ? 'radio-inline' : '' }} form-check-label font-weight-normal">{!! $label !!}</label>
            </div>
        @endforeach
    @endif
    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
    @push('after_scripts')
    @loadOnce('bpFieldInitRadioElement')
    <script>
        function bpFieldInitRadioElement(element) {
            var hiddenInput = element.find('input[type=hidden]');
            var value = hiddenInput.val();
            var id = 'radio_'+Math.floor(Math.random() * 1000000);
            // set unique IDs so that labels are correlated with inputs
            element.find('.form-check input[type=radio]').each(function(index, item) {
                $(item).attr('id', id+index);
                $(item).siblings('label').attr('for', id+index);
            });
            hiddenInput.on('CrudField:disable', function(e) {
                element.find('.form-check input[type=radio]').each(function(index, item) {
                    $(this).prop('disabled', true);
                });
            });

            hiddenInput.on('CrudField:enable', function(e) {
                element.find('.form-check input[type=radio]').each(function(index, item) {
                    $(this).removeAttr('disabled');
                });
            });

            // when one radio input is selected
            element.find('input[type=radio]').change(function(event) {
                // the value gets updated in the hidden input and the 'change' event is fired
                hiddenInput.val($(this).val()).change();
                // all other radios get unchecked
                element.find('input[type=radio]').not(this).prop('checked', false);
            });

            // select the right radios
            element.find('input[type=radio][value="'+value+'"]').prop('checked', true);
        }
    </script>
    @endLoadOnce
    @endpush
<!-- radio.balde -->
