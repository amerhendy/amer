@php
  $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
  $field['wrapper']['class'] = $field['wrapper']['class'] ?? 'form-group col-sm-12';
  $field['wrapper']['class'] = $field['wrapper']['class'].' checklist_dependency';
  $field['wrapper']['data-entity'] = $field['wrapper']['data-entity'] ?? $field['field_unique_name'];
  $field['wrapper']['data-init-function'] = $field['wrapper']['init-function'] ?? 'bpFieldInitChecklistDependencyElement';
  $field['wrapper']['element'] = $field['wrapper']['element'] ?? 'div';
@endphp
<{{ $field['wrapper']['element'] }}
	@foreach($field['wrapper'] as $attribute => $value)
	    {{ $attribute }}="{{ $value }}"
	@endforeach
>
<?php
    $guardsfield=$field['subfields']['primary'];
    $guardsarray=$guardsfield['options'];
    $permissionfiels=$field['subfields']['secondary'];
    $permissionmodel=$permissionfiels['model'];
    $guardsarraypermession=[];
    foreach($guardsarray as $key=>$item){
        $perms=$permissionmodel::where('guard_name',$item)->get()->toArray();
        $guardsarraypermession[$key]=$perms;
    }
    
?>
<div class="container">
        <div class="row">
            <div class="col-sm-12">
                <label>{!! $guardsfield['label'] !!}</label>
            </div>
        </div>
        <div class="row">
            <div class="hidden_fields_primary" data-name = "{{ $guardsfield['name'] }}">
                @if(isset($field['value']))
                    @if(old($guardsfield['name']))
                        @foreach( old($guardsfield['name']) as $item )
                        <input type="hidden" class="primary_hidden" name="{{ $guardsfield['name'] }}" /value="{{ $item }}">
                        @endforeach
                    @else
                    <input type="hidden" class="primary_hidden" name="{{ $guardsfield['name'] }}" value="{{ $field['value'][0] }}">
                    @endif
                @endif
            </div>
        </div>
</div>
<div class="row">
    @foreach($guardsarray as $guarditem=>$item)
    <div class="col-sm-{{ isset($guardsfield['number_columns']) ? intval(12/$guardsfield['number_columns']) : '4'}}" >
        <div class="checkbox custom-control custom-checkbox py-1 list-group-item" id="mainmir_{{ $guarditem }}">
        <input
                type="radio"
                data-id = "{{ $guarditem }}"
                class="custom-control-input primary_list btn-check"
                value="{{ $guarditem }}"
                id="mir_{{ $guarditem }}"
                @foreach ($guardsfield as $attribute => $value)
                    @if (is_string($attribute) && $attribute != 'value' && is_string($value))
                        @if ($attribute=='name')
                        {{ $attribute }}="{{ $value }}_show"
                        @else
                        {{ $attribute }}="{{ $value }}"
                        @endif
                    @endif
                    @if(isset($field['value'][0]) && $field['value'][0]== $guarditem) checked = "checked" @endif
                @endforeach
                >
                <label class="btn btn-secondary" for="mir_{{ $guarditem }}">
                    {{ $guarditem}}
                </label>
        </div>
    </div>
    @endforeach
</div>
<?php
$dependencyJson = json_encode($guardsarraypermession);
$CASJSON=   json_encode($guardsarray);
?>
<input id='json' type="hidden" value='{!! $CASJSON !!}'>
<div class="row">
    <div class="col-sm-12">
        <label>{!! $permissionfiels['label'] !!}</label>
    </div>
</div>
<div class='permissions'></div>
@push('after_scripts')
    <script>
        var  {{ $field['field_unique_name'] }} = {!! $dependencyJson !!};
        var  guards = {!! $CASJSON !!};
    </script>
@endpush
@push('after_scripts')
<script>
    var idCurrent=$('.primary_list[checked="checked"]').val();
    var realJson =JSON.parse($('#json').val());
    var unique_name = $('.checklist_dependency').data('entity');
    var dependencyJson = window[unique_name];
    loadselected(idCurrent);
    $('.primary_list').on('change',function(){
        $('.permissions').html('');
        loadselected($(this).val());
    });
    function loadselected (selected){
        $('.primary_list').each(function(k,v){
            var parent=$(v).parent().children();
            var wan=parent[1];
            $(wan).attr('class','btn btn-secondary');
        });
        wantedlabel=$('label[for="mir_'+selected+'"]');
        if(wantedlabel.length !== 0){
            $(wantedlabel).attr('class','btn btn-primary');
        }
        var realJson =window['guards'];
        var unique_name = $('.checklist_dependency').data('entity');
        var dependencyJson = window[unique_name];
        
        var html='';
        html+='<div class="container">';
            html+='<div class="row">';    
        $.each(dependencyJson[selected], function(key, value){
            console.log(value);
            html+='<div class="col-sm-4">';
                html+=`<div class="checkbox custom-control custom-checkbox py-1 list-group-item list-group-item-success form-check">
                        <input name="permissions[]" class="form-check-input" type="checkbox" value="`+value['id']+`" id="flexCheckCheckedDisabled" checked>
                        <label class="form-check-label" for="flexCheckCheckedDisabled">
                            `+value['name']+`
                        </label>
                        </div>`;
        html+='</div>';
            //alert(value);
        });
            html+='</div>';
        html+='</div>';
        $('.permissions').append(html);
        var dpd=$('input[name="guard_name"]');
        dpd.val(selected);
    }
    function convert_json(json){
        var bo=Array();
        $.each(json, function(k,v){
            bo[v['id']]=Array(v['id'],v['name'],v['trans_name']);
        })
        return bo;
    }
</script>
@endpush