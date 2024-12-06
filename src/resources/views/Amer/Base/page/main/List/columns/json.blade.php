<!-- json -->
@php
    $column['value'] = $column['value'] ?? $entry->{$column['name']};
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['wrapper']['element'] = $column['wrapper']['element'] ?? 'pre';
    $column['text'] = $column['default'] ?? '-';
    $column['trans'] = $column['trans'] ?? null;
    $dataid=$entry['id'];
    if(is_string($column['value'])) {
        $column['value'] = json_decode($column['value'], true);
    }
    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }
    if(isset($column['get'])){
        /*
        $column['value']=[
            ['id'=>'1','govs'=>'dfd1'],
            ['id'=>'2','govs'=>'dfd2'],
            ['id'=>'3','govs'=>'dfd3'],
            ['id'=>'4','govs'=>'dfd4'],
        ];*/
        $column['value']=AmerHelper::getDataFromJSON($column);
    }
    if(!empty($column['value'])) {
        if(gettype($column['value']) == 'string'){
            $prepearforjsjavascript=$column['value'];
            $column['text']=$column['prefix'].Str::limit($prepearforjsjavascript,100).$column['suffix'];
        }else{

            $prepearforjsjavascript=json_encode($column['value'],  JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $column['text'] = '<code style="unicode-bidi:embed" onclick="viewjson(this)" data-id="'.\Str::before($dataid, '-').'">
            '.$column['prefix'].Str::limit($prepearforjsjavascript,100).$column['suffix'].'
            </code>';
        }


    }

@endphp


@if($column['escaped'])
{!! $column['text'] !!}
@else
{!! $column['text'] !!}
@endif
<script>
    var json_code_{{\Str::before($dataid, '-')}} ='@json($column["value"])';
</script>
