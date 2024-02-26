<!-- address.blade -->
@php
    $language=str_replace('_', '-', app()->getLocale());
    $field['store_as_json'] = $field['store_as_json'] ?? false;
    $field['value'] = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';

    // the field should work whether or not Laravel attribute casting is used
    if (isset($field['value']) && (is_array($field['value']) || is_object($field['value']))) {
        $field['value'] = json_encode($field['value']);
    }
    $field['delay'] = $field['delay'] ?? 500;
@endphp
        <select
        name="{{ $field['name'] }}"
        style="width:100%"
        data-init-function="bpFieldInitSelect2FromAjaxPingMaps"
        data-placeholder="{{ $field['placeholder'] ?? ''}}"
        data-minimum-input-length="{{ $field['minimum_input_length'] ?? 2}}"
        data-data-source="https://dev.virtualearth.net/REST/v1/Locations/"
        data-ajax-delay="{{ $field['delay'] }}"
        data-language="{{ $language }}"
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control'])
        ></select>
    @push('after_styles')
    @loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
    @loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap-5-theme.rtl.min.css')
    @endpush
    @push('after_scripts')
    @loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
    @loadScriptOnce('js/packages/select2/dist/js/i18n/'.$language.'.js')
    @loadOnce('bpFieldInitSelect2FromAjaxPingMaps')
    <script>
       function bpFieldInitSelect2FromAjaxPingMaps(element){
        var form = element.closest('form');
        var $placeholder = element.attr('data-placeholder');
        var $minimumInputLength = element.attr('data-minimum-input-length');
        var $dataSource = element.attr('data-data-source');
        var $ajaxDelay = element.attr('data-ajax-delay');
        var $isFieldInline = element.data('field-is-inline');
        var select2AjaxMultipleFetchSelectedEntries = function (element) {
            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: $dataSource,
                    data: {
                        'keys': $selectedOptions
                    },
                    type: $method,
                    success: function (result) {

                        resolve(result);
                    },
                    error: function (result) {
                        reject(result);
                    }
                });
            });
        };
        if (!$(element).hasClass("select2-hidden-accessible"))
        {
            $(element).select2({
                theme: 'bootstrap-5',
                placeholder: $placeholder,
                minimumInputLength: $minimumInputLength,
                dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : document.body,
                ajax: {
                    url: $dataSource,
                    async:true,
                    cache:true,
                    crossDomain:true,
	                contentType:"application/x-www-form-urlencoded; charset=UTF-8",
                    beforeSend:function(xhr){
                        
                    },
                    formatResult: formatState,
	                templateResult: formatState,
                    delay: $ajaxDelay,
                    data:function(params){
                        var query = {
                            q:params.term,
                            maxResults:25,
                            key:"{{config('amer.ping.Maps.Key') ?? null}}",
                        }
                        return query;
                    },
                    processResults: function (data) {
                            if(!Array.isArray(data['resourceSets'])){return;}
                            if(data['resourceSets'][0]['resources'].length == 0) {return;}
                            var data = data['resourceSets'][0]['resources'];
                            return {
                                results: $.map(data, function (item,key) {
                                    var dod=''
                                    var addressarray=item['address'];
                                    var $adress=[];
                                    if(addressarray['adminDistrict4']){$adress.push(addressarray['adminDistrict4']);}
                                    if(addressarray['adminDistrict3']){$adress.push(addressarray['adminDistrict3']);}
                                    if(addressarray['adminDistrict2']){$adress.push(addressarray['adminDistrict2']);}
                                    if(addressarray['adminDistrict1']){$adress.push(addressarray['adminDistrict1']);}
                                    if(addressarray['adminDistrict']){$adress.push(addressarray['adminDistrict']);}
                                    dod+=" "+item.name+" ("+addressarray['countryRegion']+" - "+$adress.toString()+")";
                                    var coordinates=item.geocodePoints[0].coordinates;
                                    var mol=coordinates.toString()
                                        return {
                                            text:" "+item.name+" ("+addressarray['countryRegion']+" - "+$adress.toString()+")",
                                            id:mol,
                                            
                                        };
                                    })
                            };
                            }
                }
            });
        }
       }
       function formatState (state) {
  if (!state.id) { return state.text; }
  var $state = $(
    '<i class="fa fa-map-marker" aria-hidden="true"></i>' + 
state.text +     ''
 );
 return $state;
}
    </script>

    @endLoadOnce
    @endpush