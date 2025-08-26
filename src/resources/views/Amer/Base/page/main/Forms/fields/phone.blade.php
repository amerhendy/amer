<!-- phone input -->
<?php
/*
AMER::addField([
            'name'  => 'phone', // db column for phone
            'label' => 'Phone',
            'type'  => 'phone',
            //'value'=>'+447733312345',
            'config' => [
                'onlyCountries' => ["al", "ad", "at", "by", "be", "ba", "bg", "hr", "cz", "dk",
                "ee", "fo", "fi", "fr", "de", "gi", "gr", "va", "hu", "is", "ie", "it", "lv",
                "li", "lt", "lu", "mk", "mt", "md", "mc", "me", "nl", "no", "pl", "pt", "ro",
                "ru", "sm", "rs", "sk", "si", "es", "se", "ch", "ua", "gb",'eg'],
                'initialCountry' => 'eg', // this needs to be in the allowed country list, either in `onlyCountries` or NOT in `excludeCountries`
                'placeholderNumberType' => 'MOBILE',//FIXED_LINE,MOBILE,FIXED_LINE_OR_MOBILE,TOLL_FREE,PREMIUM_RATE,SHARED_COST,VOIP,PERSONAL_NUMBER,PAGER,UAN,VOICEMAIL,VOICEMAIL
            ]
        ]);
 */
$config=[];
$config['utilsScript']=asset('js/packages/intl-tel-input-18.2.1/build/js/utils.js');
$config['hiddenInput']=$field['name'];
$config['separateDialCode'] = true;
$config['nationalMode'] = true;
$config['autoHideDialCode'] = false;
if(isset($field['config'])){
    if(is_array($field['config'])){
        foreach($field['config'] as $a=>$b){
            $config[$a]=$b;
        }
    }
}
?>
    <div>
        <span class="error"></span>
        <br>
        <input type="tel" id="{{$field['name']}}" data-init-function="bpFieldInitphoneElement" value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}" @include(fieldview('inc.attributes')) >
    </div>
    
@push('after_styles')
    @loadStyleOnce('js/packages/intl-tel-input-18.2.1/build/css/intlTelInput.min.css')
@endpush
@push('after_scripts')
@loadScriptOnce('js/packages/intl-tel-input-18.2.1/build/js/intlTelInput.min.js')
@loadOnce('bpFieldInitphoneElement')
<script>
    function bpFieldInitphoneElement(element){
        var input = document.querySelector("#"+element.attr('id'));
        var config = {{ Illuminate\Support\Js::from($config) }};
        var errorspan=element.siblings('span');
        var hiddenInput=$('input[name='+config.hiddenInput+']');
        const iti=window.intlTelInput(input, config);
        $(input).on('input',function(){
            number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
            error  = iti.getValidationError();
            $(hiddenInput).val(number);
            if (error === intlTelInputUtils.validationError.TOO_SHORT) {
                $(errorspan).html('{{trans("AMER::Base.TOO_SHORT")}}');
            }else if (error === intlTelInputUtils.validationError.TOO_LONG) {
                $(errorspan).html('{{trans("AMER::Base.TOO_LONG")}}');
            }else if (error === intlTelInputUtils.validationError.IS_POSSIBLE_LOCAL_ONLY) {
                $(errorspan).html('{{trans("AMER::Base.IS_POSSIBLE_LOCAL_ONLY")}}');
            }else  if (error === intlTelInputUtils.validationError.INVALID_COUNTRY_CODE) {
                $(errorspan).html('{{trans("AMER::Base.INVALID_COUNTRY_CODE")}}');
            }else  if (error === intlTelInputUtils.validationError.INVALID_LENGTH) {
                $(errorspan).html('{{trans("AMER::Base.INVALID_LENGTH")}}');
            }else  if (error === intlTelInputUtils.validationError.IS_POSSIBLE) {
                $(errorspan).html('{{trans("AMER::Base.IS_POSSIBLE")}}');
            }else{
                $(errorspan).html('');
            }
        })
    }
  
</script>
@endLoadOnce
    @endpush