<!-- bootstrap daterange picker input -->

<?php
$sqlFormat='d/m/Y H:i:s';
$jsformat='MM/DD/YYYY HH:mm:ss';
    // if the column has been cast to Carbon or Date (using attribute casting)
    // get the value as a date string
    if (! function_exists('formatDate')) {
        function formatDate($entry, $dateFieldName)
        {
            $sqlFormat='d/m/Y H:i:s';
            $formattedDate = null;
            if (isset($entry) && ! empty($entry->{$dateFieldName})) {
                $dateField = $entry->{$dateFieldName};
                if ($dateField instanceof \Carbon\CarbonInterface) {
                    $formattedDate = $dateField->format($sqlFormat);
                } else {
                    $formattedDate = date($sqlFormat, strtotime($entry->{$dateFieldName}));
                }
            }

            return $formattedDate;
        }
    }
    if(!is_array($field['name'])){
        if(\Str::contains($field['name'],',')){
            $field['name']=explode(',',$field['name']);
        }
    }
    if (isset($entry)) {
        $start_value = formatDate($entry, $field['name'][0]);
        $end_value = formatDate($entry, $field['name'][1]);
    }
    $start_default = $field['default'][0] ?? date($sqlFormat);
    $end_default = $field['default'][1] ?? date($sqlFormat);
    // make sure the datepicker configuration has at least these defaults
    [$engmonths, $arrmonthes] = Arr::divide(trans("AMER::trojan.months"));
    [$engdays, $arrdays] = Arr::divide(trans("AMER::trojan.days"));
    $field['date_range_options'] = array_replace_recursive([
        'autoApply' => true,
        'startDate' => '09/29/2023',
        'endDate' => $end_default,
        'showDropdowns'=> true,
        'timePicker'=>true,
        'timePickerSeconds'=>true,
        "weekLabel"=> "W",
        
        "alwaysShowCalendars"=> true,
        "opens"=> "center",
        "drops"=> "auto",
        'locale' => [
            "daysOfWeek"=> $arrdays,
            "monthNames"=> $arrmonthes,
            "fromLabel"=> "From",
            "toLabel"=> "To",
            "customRangeLabel"=> "Custom",
            'firstDay' => 0,
            'format' => $jsformat,
            'applyLabel'=> trans('AMER::Base.apply'),
            'cancelLabel'=> trans('AMER::Base.cancel'),
        ],
    ], $field['date_range_options'] ?? []);
    if(is_array($field['name'])){
        $for=implode('_',$field['name']);
    }
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="$for" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>

    <input class="datepicker-range-start" type="hidden" name="{{ $field['name'][0] }}" value="{{ old(square_brackets_to_dots($field['name'][0])) ?? $start_value ?? $start_default ?? '' }}">
    <input class="datepicker-range-end" type="hidden" name="{{ $field['name'][1] }}" value="{{ old(square_brackets_to_dots($field['name'][1])) ?? $end_value ?? $end_default ?? '' }}">
    <label>{!! $field['label'] !!}</label>
    <div class="input-group date">
        <input
            data-bs-daterangepicker="{{ json_encode($field['date_range_options'] ?? []) }}"
            data-init-function="bpFieldInitDateRangeElement"
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
    @loadStyleOnce('js/packages/bootstrap-daterangepicker/daterangepicker.css')
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('after_scripts')
    @loadScriptOnce('js/packages/moment/moment.js')
    @loadScriptOnce('js/packages/bootstrap-daterangepicker/daterangepicker.js')
    @loadOnce('bpFieldInitDateRangeElement')
    <script>
        function bpFieldInitDateRangeElement(element) {
                moment.locale('{{app()->getLocale()}}');

                var $visibleInput = element;
                var $startInput = $visibleInput.closest('.input-group').parent().find('.datepicker-range-start');
                var $endInput = $visibleInput.closest('.input-group').parent().find('.datepicker-range-end');

                var $configuration = $visibleInput.data('bs-daterangepicker');
                $configuration.startDate=new Date($configuration.startDate);
                $configuration.endDate=new Date($configuration.endDate);
                // set the startDate and endDate to the defaults
                $configuration.startDate = moment($configuration.startDate);
                $configuration.endDate = moment($configuration.endDate);
                
                // if the hidden inputs have values
                // then startDate and endDate should be the values there
                if ($startInput.val() != '') {
                    $configuration.startDate = moment($startInput.val());
                }
                if ($endInput.val() != '') {
                    $configuration.endDate = moment($endInput.val());
                }
                $configuration.ranges={
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                            };
                //return;
                $visibleInput.daterangepicker($configuration);

                var $picker = $visibleInput.data('daterangepicker');
                //console.log($visibleInput.data('daterangepicker'));
                $visibleInput.on('keydown', function(e){
                    e.preventDefault();
                    return false;
                });

                $visibleInput.on('apply.daterangepicker hide.daterangepicker', function(e, picker){
                    $startInput.val( picker.startDate.format('{{$jsformat}}') );
                    $endInput.val( picker.endDate.format('{{$jsformat}}') );
                });
        }
    </script>
    @endLoadOnce
    @endpush
{{-- End of Extra CSS and JS --}}
