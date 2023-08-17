{{-- regular object attribute --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['limit'] = $column['limit'] ?? 32;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['text'] = $column['default'] ?? '-';
    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }
    if(is_array($column['value'])) {
        $column['value'] = json_encode($column['value']);
    }
    if(!empty($column['value'])) {
        $mp3=$column['value'];
    }else{
        $mp3="AAA";
    }
@endphp

<span>
    @includeWhen(!empty($column['wrapper']), listview('columns.inc.wrapper_start'))
    @if(!empty($column['value']))
    <audio controls>
        <source src="{{$mp3}}" type="audio/ogg">
        {{$mp3}}
    </audio> 
    @else
    -
    @endif
    @includeWhen(!empty($column['wrapper']), listview('columns.inc.wrapper_end'))
</span>
