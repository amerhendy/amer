<!-- select2 -->
    <?php
    $entity_model = $Amer->getModel();
    if(isset($field['orderby'])){
        $cond=$field['model']::orderby($field['orderby'])->get();
    }else{
        $cond=$field['model']::all();
    }
    ?>
    <div class="row">
        <div class="col-sm-4">
            <div class="checkbox  py-1 list-group-item list-group-item-primary">
            <label class="font-weight-normal">{{trans('AMER::actions.selectall')}}</label>
                <input type="checkbox" class="control-input border" id="selectall">
            </div>
        </div>
        @foreach ($cond as $connected_entity_entry)
            <div class="col-sm-4">
                <div class="checkbox py-1 list-group-item list-group-item-success">
                    <?php
                    $label=[];
                    if(is_array($field['attribute'])){
                        foreach($field['attribute'] as $k){
                            $label[]=$connected_entity_entry->$k;
                        }
                    }else{
                        $label[]=$connected_entity_entry->{$field['attribute']};
                    }
                    $label=array_filter($label, fn($value) => !is_null($value) && $value !== '');
                    ?>
                  <label class="font-weight-normal">{!! implode(' - ',$label) !!}</label>
                    <input type="checkbox" class="control-input border"
                      name="{{ $field['name'] }}[]"
                      value="{{ $connected_entity_entry->getKey() }}"

                      @if( ( old( $field["name"] ) && in_array($connected_entity_entry->getKey(), old( $field["name"])) ) || (isset($field['value']) && in_array($connected_entity_entry->getKey(), $field['value']->pluck($connected_entity_entry->getKeyName(), $connected_entity_entry->getKeyName())->toArray())))
                             checked = "checked"
                      @endif >
                </div>
            </div>
        @endforeach

    </div>
    @push('after_scripts')
        <script type="text/javascript">
            $("#selectall").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
            });
        </script>
        @endpush
