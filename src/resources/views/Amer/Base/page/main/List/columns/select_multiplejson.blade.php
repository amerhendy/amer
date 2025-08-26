{{-- relationships with pivot table (n-n) --}}
<?php
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['limit'] = $column['limit'] ?? 10;
    $column['attribute'] = $column['attribute'] ?? (new $column['model'])->identifiableAttribute();
    $model=new $column['model']();
    $results = data_get($entry, $column['name']);
    $results_array = [];
    if(gettype($results) == 'string'){
        if(Str::isJson($results)){
            $results=json_decode($results);
        }
    }
    $results=($model->whereIn('id',$results)->get());
    if($results !== null && !$results->isEmpty()) {
        $related_key = $results->first()->getKeyName();
        if(!is_array($column['attribute']))
        {
            $results_array = $results->pluck($column['attribute'], $related_key)->toArray();
        }
        else
        {
            $sd=[];
            foreach($results->toArray() as $a=>$b){
                foreach($column['attribute'] as $c=>$d){
                    if(isset($column['array_view']['enum'][$d][$b[$d]])){
                        $sd[$a][$d]=$column['array_view']['enum'][$d][$b[$d]];
                    }else{
                        $sd[$a][$d]=$b[$d];
                    }
                }
            }
            if(isset($column['array_view']['translate'])){
                $string = $column['array_view']['translate'];
                foreach($sd as $a=>$b){
                    $sd[$a] = Str::replaceArray('?', $b, $string);
                }
            }else{
                if(isset($column['array_view']['divider'])){$div=$column['array_view']['divider'];}else{$div=',';}
                
                foreach($sd as $a=>$b){
                    $sd[$a] = implode($div,$b);
                }
            }
            $results_array=$sd;
        }
    }

    foreach ($results_array as $key => $text) {
        $results_array[$key] =\AmerHelper::createhtmllimitstring($text);//Str::limit($text, $column['limit'], '[...]');
    }
?>

<span>
    @if(!empty($results_array))
        {{ $column['prefix'] }}
        @foreach($results_array as $key => $text)
            @php
            
                $related_key = $key;
            @endphp

            <span class="d-inline-flex">
                @includeWhen(!empty($column['wrapper']), listview('columns.inc.wrapper_start'))
                        {!! $text !!}
                @includeWhen(!empty($column['wrapper']), listview('columns.inc.wrapper_end'))

                @if(!$loop->last), @endif
            </span>
        @endforeach
        {{ $column['suffix'] }}
    @else
        -
    @endif
</span>
