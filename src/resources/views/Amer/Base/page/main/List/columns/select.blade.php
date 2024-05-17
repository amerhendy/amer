{{-- single relationships (1-1, 1-n) --}}
<?php
    $column['attribute'] = $column['attribute'] ?? (new $column['model'])->identifiableAttribute();
    $key='';
    if(!is_array($column['attribute'])){
        $column['value'] = $column['value'] ?? $Amer->getRelatedEntriesAttributes($entry, $column['entity'], $column['attribute']);
    }else{
        $vals=[];
        foreach($column['attribute'] as $a=>$b){
            $b=$Amer->getRelatedEntriesAttributes($entry, $column['entity'], $b);
            $key=array_keys($b)[0];
            $vals[]=$b[$key];
        }
        if(!isset($column['array_view'])){
            $column['value']=implode(' - ',$vals);
        }else{
            if(isset($column['array_view']['translate'])){
                $column['value']=(Str::replaceArray('?', $vals, $column['array_view']['translate']));
            }elseif(isset($column['array_view']['divider'])){
                $column['value']=implode(" ".$column['array_view']['divider']." ",$vals);
            }else{
                $column['value']=implode(' - ',$vals);
            }
        }
        $column['value']=[$key=>$column['value']];
    }
    
    //dd($column['entity'],$column['attribute'],$column['value']);

    
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['limit'] = $column['limit'] ?? 32;

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    foreach ($column['value'] as &$value) {
        $value = Str::limit($value, $column['limit'], '…');
    }
?>

<span>
    @if(count($column['value']))
        {{ $column['prefix'] }}
        @foreach($column['value'] as $key => $text)
            @php
                $related_key = $key;
            @endphp

            <span class="d-inline-flex">
                @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
                    @if($column['escaped'])
                        {{ $text }}
                    @else
                        {!! $text !!}
                    @endif
                @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')

                @if(!$loop->last), @endif
            </span>
        @endforeach
        {{ $column['suffix'] }}
    @else
        {{ $column['default'] ?? '-' }}
    @endif
</span>
