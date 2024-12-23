{{-- switch field --}}
@php
    $field['value'] = old_empty_or_null($field['name'], '') ?? $field['value'] ?? $field['default'] ?? '0';
    $field['onLabel'] = $field['onLabel'] ?? '';
    $field['offLabel'] = $field['offLabel'] ?? '';
    $field['color'] = $field['color'] ?? 'primary';
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>

    <div class="d-inline-flex">
        {{-- Switch --}}
        <label class="switch switch-sm switch-label switch-pill switch-{{ $field['color'] }} mb-0" style="--bg-color: {{ $field['color'] }};">
            <input
                type="hidden"
                name="{{ $field['name'] }}"
                value="{{ (int) $field['value'] }}" />
            <input
                type="checkbox"
                data-init-function="bpFieldInitSwitch"
                {{ (bool) $field['value'] ? 'checked' : '' }}
                class="switch-input" />
            <span
                class="switch-slider"
                data-checked="{{ $field['onLabel'] ?? '' }}"
                data-unchecked="{{ $field['offLabel'] ?? '' }}">
            </span>
        </label>

        {{-- Label --}}
        <label class="font-weight-normal mb-0 ml-2">{!! $field['label'] !!}</label>
    </div>

    {{-- Label for the required * --}}
    <label class="d-inline-flex m-0">&nbsp;</label>

    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
@push('after_scripts')
    @loadOnce('bpFieldInitSwitchScript')
    <script>
        function bpFieldInitSwitch($element) {
            let element = $element[0];
            let hiddenElement = element.previousElementSibling;
            let id = `switch_${hiddenElement.name}_${Math.random() * 1e18}`;

            // set unique IDs so that labels are correlated with inputs
            element.setAttribute('id', id);
            element.parentElement.nextElementSibling.setAttribute('for', id);

            // set the default checked/unchecked state
            // if the field has been loaded with javascript
            hiddenElement.value !== '0'
                ? element.setAttribute('checked', true)
                : element.removeAttribute('checked');

            // JS Field API
            $(hiddenElement).on('CrudField:disable', () => element.setAttribute('disabled', true));
            $(hiddenElement).on('CrudField:enable', () => element.removeAttribute('disabled'));

            // when the checkbox is clicked
            // set the correct value on the hidden input
            $element.on('change', () => {
                hiddenElement.value = element.checked ? 1 : 0;
                hiddenElement.dispatchEvent(new Event('change'));
            });
        }
    </script>
    @endLoadOnce
@endpush

@push('after_styles')
    @loadOnce('bpFieldInitSwitchStyle')
    <style>
        .switch-input:checked+.switch-slider {
            background-color: var(--bg-color);
        }
    </style>
    @endLoadOnce
@endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
