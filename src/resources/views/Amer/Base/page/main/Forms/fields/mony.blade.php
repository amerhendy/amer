{{-- mony input --}}
<!-- mony -->
@php
if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
if(isset($field['min'])){$field['attributes']['min']=(int) $field['min'];}
if(isset($field['max'])){$field['attributes']['max']=(int) $field['max'];}
if(isset($field['step'])){$field['attributes']['step']=$field['step'];}
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
        <input
            placeholder="{{ $field['placeholder'] }}"
        	type="currency"
        	name="{{ $field['name'] }}"
            id="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            data-init-function="bpFieldInitmonyinputElement"
            data-currency={{$field['currency'] ?? 'EGP'}}
            @include(fieldview('inc.attributes'))
        	>
    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
@push('after_scripts')
@loadOnce('bpFieldInitmonyinputElement')
<script>
    function bpFieldInitmonyinputElement(element)
    {
        var currency=$(element).attr('data-currency');
        onBlur(element);
        $(element).on('focus',function(e){onFocus(this);})
        $(element).on('blur',function(e){onBlur(this);})
        //element.addEventListener('focus', onFocus)
        //element.addEventListener('blur', onBlur)
        function onBlur(e){
            var value = $(e).val();
            var options = {
                maximumFractionDigits : 2,
                currency              : currency,
                style                 : "currency",
                currencyDisplay       : "symbol"
            }

            e.value = (value || value === 0)
                ? localStringToNumber(value).toLocaleString(undefined, options)
                : '';
        }
        function localStringToNumber( s ){
            return Number(String(s).replace(/[^0-9.,-]+/g,""))
        }
        function onFocus(e){
            var value = $(e).val();
            e.value = value ? localStringToNumber(value) : ''
        }
    }
</script>
@endLoadOnce
@endpush
