<!-- dependencyJson -->
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
    
        $user_model = $Amer->getModel();
        $team_dependency = $field['subfields']['zero'];
        $role_dependency = $field['subfields']['primary'];
        //dd($field);
        $teams=$team_dependency['model']::all();
        dd($team_dependency['model']::get()->toArray());

        $permission_dependency = $field['subfields']['secondary'];
        $roles = $role_dependency['model']::with($role_dependency['entity_secondary'])->get();
        
        $rolesArray = [];
        foreach ($roles as $primary) {
            $rolesArray[$primary->id] = [];
            foreach ($primary->{$role_dependency['entity_secondary']} as $secondary) {
                $rolesArray[$primary->id][] = $secondary->id;
            }
        }
        if(count(amer_user()->roles)){
            $sort=amer_user()->roles[0]['sort'];
        }else{
            $sort=1;
        }
      if (isset($id) && $id) {
        //get entity with relations for primary dependency
          $entity_dependencies = $user_model->with($role_dependency['entity'])
          ->with($role_dependency['entity'].'.'.$role_dependency['entity_secondary'])
          ->find($id);
          $secondaries_from_primary = [];
          //\DB::enableQueryLog();
          $alo=$user_model->with('roles')->with($role_dependency['entity'].'.'.$role_dependency['entity_secondary'])->where('id',1);
          //dd(\DB::getQueryLog());
            //dd($entity_dependencies->get()->toArray());
          //convert relation in array
          //dd($entity_dependencies);
          $primary_array = $entity_dependencies->{$role_dependency['entity']}->toArray();
          
          //print '<pre>';
          //print_r(amer_user()->roles[0]['sort']);

          $secondary_ids = [];
          //create secondary dependency from primary relation, used to check what chekbox must be check from second checklist
          if (old($role_dependency['name'])) {
              foreach (old($role_dependency['name']) as $primary_item) {
                  foreach ($rolesArray[$primary_item] as $second_item) {
                      $secondary_ids[$second_item] = $second_item;
                  }
              }
          } else { //create dependecies from relation if not from validate error
              foreach ($primary_array as $primary_item) {
                  foreach ($primary_item[$permission_dependency['entity']] as $second_item) {
                      $secondary_ids[$second_item['id']] = $second_item['id'];
                  }
              }
          }
      }
        //json encode of dependency matrix
        $dependencyJson = json_encode($rolesArray);
    ?>
    <div class="container teams">
            <div class="row">
                <div class="col-sm-12">
                    <label>{!! $team_dependency['label'] !!}</label>
                </div>
            </div>
            <div class="row">
                @foreach($teams as $connected_entity_entry)
<?php 
//dd($field['value']);
?>
                <div class="col-sm-{{ isset($team_dependency['number_columns']) ? intval(12/$team_dependency['number_columns']) : '4'}}" >
                    <div class="checkbox custom-control custom-checkbox py-1 list-group-item" id="mainTeam_{{ $connected_entity_entry->id }}">
                        <input
                        type="radio"
                        data-id = "{{ $connected_entity_entry->id }}"
                        class="custom-control-input zero_list btn-check"
                        value="{{ $connected_entity_entry->id }}"
                        id="Team_{{ $connected_entity_entry->id }}"
                        @foreach ($team_dependency as $attribute => $value)
                                    @if (is_string($attribute) && $attribute != 'value')
                                        @if ($attribute=='name')
                                        {{ $attribute }}="{{ $value }}_show[]"
                                        @else
                                        {{ $attribute }}="{{ $value }}"
                                        @endif
                                    @endif
                        @endforeach
                        @if( ( isset($field['value']) && is_array($field['value']) && in_array($connected_entity_entry->id, $field['value'][0   ]->pluck('id', 'id')->toArray())) || ( old($role_dependency["name"]) && in_array($connected_entity_entry->id, old( $role_dependency["name"])) ) )
                                checked = "checked"
                                @endif
                                >
                        <label class="btn btn-secondary" for="Team_{{ $connected_entity_entry->id }}">
                            {{ $connected_entity_entry->{$team_dependency['attribute']} }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <label>{!! $role_dependency['label'] !!}</label>
            </div>
        </div>
        <div class="row">
            <div class="hidden_fields_primary" data-name = "{{ $role_dependency['name'] }}">
                @if(isset($field['value']))
                    @if(old($role_dependency['name']))
                        @foreach( old($role_dependency['name']) as $item )
                        <input type="hidden" class="primary_hidden" name="{{ $role_dependency['name'] }}[]" value="{{ $item }}">
                        @endforeach
                    @else
                        @if(count($field['value'][0]->pluck('id', 'id')->toArray()))
                            @foreach( $field['value'][0]->pluck('id', 'id')->toArray() as $item )
                            <input type="hidden" class="primary_hidden" name="{{ $role_dependency['name'] }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" class="primary_hidden" name="{{ $role_dependency['name'] }}[]" value="">
                        @endif
                        
                    @endif
                @endif
            </div>
    </div>
    <div class="row roles">
        @php
        if(isset($role_dependency['order'])){
            if(isset($sort)){
                $ACS = $role_dependency['model']::where('sort','>=',$sort)->orderby($role_dependency['order'])->get();
            }else{
                $ACS = $role_dependency['model']::orderby($role_dependency['order'])->get();
            }
        }else{
            $ACS=$role_dependency['model']::all();
        }
        @endphp


        @foreach ($ACS as $connected_entity_entry)
        <div class="col-sm-{{ isset($role_dependency['number_columns']) ? intval(12/$role_dependency['number_columns']) : '4'}}" >
            <div class="checkbox custom-control custom-checkbox py-1 list-group-item" id="mainmir_{{ $connected_entity_entry->id }}">
                <input
                type="radio"
                data-id = "{{ $connected_entity_entry->id }}"
                class="custom-control-input primary_list btn-check"
                value="{{ $connected_entity_entry->id }}"
                id="mir_{{ $connected_entity_entry->id }}"
                @foreach ($role_dependency as $attribute => $value)
                              @if (is_string($attribute) && $attribute != 'value')
                                  @if ($attribute=='name')
                                  {{ $attribute }}="{{ $value }}_show[]"
                                  @else
                                  {{ $attribute }}="{{ $value }}"
                                  @endif
                              @endif
                          @endforeach
                @if( ( isset($field['value']) && is_array($field['value']) && in_array($connected_entity_entry->id, $field['value'][0   ]->pluck('id', 'id')->toArray())) || ( old($role_dependency["name"]) && in_array($connected_entity_entry->id, old( $role_dependency["name"])) ) )
                          checked = "checked"
                          @endif
                          >
                <label class="btn btn-secondary" for="mir_{{ $connected_entity_entry->id }}">
                    {{ $connected_entity_entry->{$role_dependency['attribute']} }}
                </label>
            </div>
        </div>
        @endforeach
    </div>
@php
if(isset($permission_dependency['order'])){
    $ACD = $permission_dependency['model']::orderby($permission_dependency['order'])->get();
  }else{
    $ACD=$permission_dependency['model']::all();
  }
  $CASJSON=   json_encode($ACD);
  //teams then roles then permissions
  dd($teams);
  dd($CASJSON);
@endphp

<input id='json' type="hidden" value="{{$CASJSON}}">
<div class="row">
    <div class="col-sm-12">
        <label>{!! $permission_dependency['label'] !!}</label>
    </div>
</div>
<div class='permissions'>
</div>
@push('after_scripts')
    <script>
        var  {{ $field['field_unique_name'] }} = {!! $dependencyJson !!};
    </script>
@endpush
    @push('after_scripts')
<script>
    (function(){
        let TeamCurrent=$('.zero_list[checked="checked"]').val();
        let realJson =JSON.parse($('#json').val());
        let unique_name = $('.checklist_dependency').data('entity');
        let dependencyJson = window[unique_name];
        loadSelectedTeams=function(selected){
            $('.zero_list').each(function(k,v){
                    var parent=$(v).parent().children();
                    var wan=parent[1];
                    $(wan).attr('class','btn btn-secondary');
                });
                wantedlabel=$('label[for="Team_'+selected+'"]');
                if(wantedlabel.length !== 0){
                    $(wantedlabel).attr('class','btn btn-primary');
                }
                let unique_name = $('.checklist_dependency').data('entity');
                var dependencyJson = window[unique_name];
                $bos =convert_json(realJson);
                var html='';
                html+='<div class="container">';
                    html+='<div class="row">';
                //alert($bos[3][1]);
                
                $.each(dependencyJson[selected], function(key, value){
                    html+='<div class="col-sm-4">';
                        html+=`<div class="checkbox custom-control custom-checkbox py-1 list-group-item list-group-item-success form-check">
                                <input name="permissions[]" class="form-check-input" type="checkbox" value="`+$bos[value][0]+`" id="flexCheckCheckedDisabled" checked>
                                <label class="form-check-label" for="flexCheckCheckedDisabled">
                                    `+$bos[value][1]+`
                                </label>
                                </div>`;
                html+='</div>';
                    //alert(value);
                });
                    html+='</div>';
                html+='</div>';
                $('.permissions').append(html);
                var dpd=$('input[name="roles[]"]');
                //console.log(dpd);
                //dpd.val(selected);
            }
        loadSelectedTeams(TeamCurrent);
        console.log(realJson,unique_name,dependencyJson);
    })(jQuery);
    
    var idCurrent=$('.primary_list[checked="checked"]').val();
    loadselected(idCurrent);
    $('.zero_list').on('change',function(){
        $('.roles').html('');
        loadSelectedTeams($(this).val());
    });
    $('.primary_list').on('change',function(){
        $('.permissions').html('');
        loadselected($(this).val());
    });
    function convert_json(json){
        var bo=Array();
        $.each(json, function(k,v){
            bo[v['id']]=Array(v['id'],v['name'],v['trans_name']);
        })
        return bo;
    }
    
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
        
        var unique_name = $('.checklist_dependency').data('entity');
        var dependencyJson = window[unique_name];
        $bos =convert_json(realJson);
        var html='';
        html+='<div class="container">';
            html+='<div class="row">';
        //alert($bos[3][1]);
        
        $.each(dependencyJson[selected], function(key, value){
            html+='<div class="col-sm-4">';
                html+=`<div class="checkbox custom-control custom-checkbox py-1 list-group-item list-group-item-success form-check">
                        <input name="permissions[]" class="form-check-input" type="checkbox" value="`+$bos[value][0]+`" id="flexCheckCheckedDisabled" checked>
                        <label class="form-check-label" for="flexCheckCheckedDisabled">
                            `+$bos[value][1]+`
                        </label>
                        </div>`;
        html+='</div>';
            //alert(value);
        });
            html+='</div>';
        html+='</div>';
        $('.permissions').append(html);
        var dpd=$('input[name="roles[]"]');
        console.log(dpd);
        dpd.val(selected);
    }
</script>
@endpush