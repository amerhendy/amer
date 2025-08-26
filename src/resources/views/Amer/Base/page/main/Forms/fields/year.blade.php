<!-- date_picker -->
<?php
if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
    if (isset($field['value']) && ($field['value'] instanceof \Carbon\CarbonInterface)) {
        $field['value'] = $field['value']->format('Y-m-d');
    }
    $field['value'] = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
    $field_language = isset($field['date_picker_options']['language']) ? $field['date_picker_options']['language'] : \App::getLocale();
    if(isset($field['startyear'])){
        $today=now();
        $thisYear=$today->format('Y');
        $startyear=$field['startyear']-$thisYear;
        if ($startyear > 0) {$startyear = '+'.$startyear."y";}
        elseif ($startyear < 0) {$startyear = $startyear."y";}
        else{$startyear='today';}
    }
    if(isset($field['endyear'])){
        $today=now();
        $thisYear=$today->format('Y');

        $endyear=$field['endyear']-$thisYear;

        if ($endyear > 0) {$endyear = '+'.$endyear."y";}
        elseif ($endyear < 0) {$endyear = $endyear."y";}
        else{$endyear='today';}
        //dd($thisYear,$field['endyear'],$endyear);
    }

?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <div class="input-group date">
        <input type="hidden" name="{{ $field['name'] }}" value="{{$field['value']}}">
        <input
        id="{{ $field['name'] }}"
        placeholder="{{ $field['placeholder'] }}"
            data-bs-datepicker="{{ isset($field['date_picker_options']) ? json_encode($field['date_picker_options']) : '{}'}}"
            data-init-function="bpFieldInitYearPickerElement"
            type="text"
            @include(fieldview('inc.attributes'))
            >
        <div class="input-group-append">
            <span class="input-group-text">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div>
    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>

    @endif
@include(fieldview('inc.wrapper_end'))
    @push('after_styles')
    @loadStyleOnce('js/packages/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css')
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('after_scripts')
    @loadScriptOnce('js/packages/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')
    @if ($field_language !== 'en')
    @loadScriptOnce('js/packages/bootstrap-datepicker/dist/locales/bootstrap-datepicker.'.$field_language.'.min.js')
    @endif
    @loadOnce('bpFieldInitYearPickerElement')
    <script>

        if (jQuery.ui) {
            var datepicker = $.fn.datepicker.noConflict();
            $.fn.bootstrapDP = datepicker;
        } else {
            $.fn.bootstrapDP = $.fn.datepicker;
        }
        var dateFormat=function(){var a=/d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,b=/\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,c=/[^-+\dA-Z]/g,d=function(a,b){for(a=String(a),b=b||2;a.length<b;)a="0"+a;return a};return function(e,f,g){var h=dateFormat;if(1!=arguments.length||"[object String]"!=Object.prototype.toString.call(e)||/\d/.test(e)||(f=e,e=void 0),e=e?new Date(e):new Date,isNaN(e))throw SyntaxError("invalid date");f=String(h.masks[f]||f||h.masks.default),"UTC:"==f.slice(0,4)&&(f=f.slice(4),g=!0);var i=g?"getUTC":"get",j=e[i+"Date"](),k=e[i+"Day"](),l=e[i+"Month"](),m=e[i+"FullYear"](),n=e[i+"Hours"](),o=e[i+"Minutes"](),p=e[i+"Seconds"](),q=e[i+"Milliseconds"](),r=g?0:e.getTimezoneOffset(),s={d:j,dd:d(j),ddd:h.i18n.dayNames[k],dddd:h.i18n.dayNames[k+7],m:l+1,mm:d(l+1),mmm:h.i18n.monthNames[l],mmmm:h.i18n.monthNames[l+12],yy:String(m).slice(2),yyyy:m,h:n%12||12,hh:d(n%12||12),H:n,HH:d(n),M:o,MM:d(o),s:p,ss:d(p),l:d(q,3),L:d(q>99?Math.round(q/10):q),t:n<12?"a":"p",tt:n<12?"am":"pm",T:n<12?"A":"P",TT:n<12?"AM":"PM",Z:g?"UTC":(String(e).match(b)||[""]).pop().replace(c,""),o:(r>0?"-":"+")+d(100*Math.floor(Math.abs(r)/60)+Math.abs(r)%60,4),S:["th","st","nd","rd"][j%10>3?0:(j%100-j%10!=10)*j%10]};return f.replace(a,function(a){return a in s?s[a]:a.slice(1,a.length-1)})}}();dateFormat.masks={default:"ddd mmm dd yyyy HH:MM:ss",shortDate:"m/d/yy",mediumDate:"mmm d, yyyy",longDate:"mmmm d, yyyy",fullDate:"dddd, mmmm d, yyyy",shortTime:"h:MM TT",mediumTime:"h:MM:ss TT",longTime:"h:MM:ss TT Z",isoDate:"yyyy-mm-dd",isoTime:"HH:MM:ss",isoDateTime:"yyyy-mm-dd'T'HH:MM:ss",isoUtcDateTime:"UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"},dateFormat.i18n={dayNames:["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],monthNames:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec","January","February","March","April","May","June","July","August","September","October","November","December"]},Date.prototype.format=function(a,b){return dateFormat(this,a,b)};
        function bpFieldInitYearPickerElement(element) {
            var $fake = element,
                $field = $fake.closest('.input-group').parent().find('input[type="hidden"]'),
            $customConfig = $.extend({
                format: 'yyyy',
                assumeNearbyYear:true,
                defaultViewDate:'year',
                toggleActive: true,
                viewMode: "years", minViewMode: "years",
                @isset($field['startyear'])
                startDate:'{{$startyear}}',
                @endisset
                @isset($field['endyear'])
                endDate:'{{$endyear}}',
                @endisset
            }, $fake.data('bs-datepicker'));
            $picker = $fake.bootstrapDP($customConfig);
            var $existingVal = $field.val();
                if( $existingVal && $existingVal.length ){
                    preparedDate = new Date($existingVal).format($customConfig.format);
                    $fake.val(preparedDate);
                    $picker.bootstrapDP('update', preparedDate);
                }
            $picker.on('show hide change', function(e){
                    //var sqlDate = e.format('yyyy');
                    $field.val(element.val());
                });
            }
    </script>
    @endLoadOnce
    @endpush
{{-- End of Extra CSS and JS --}}
