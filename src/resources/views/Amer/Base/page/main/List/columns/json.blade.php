{{-- json --}}
@php
    $column['value'] = $column['value'] ?? $entry->{$column['name']};
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['wrapper']['element'] = $column['wrapper']['element'] ?? 'pre';
    $column['text'] = $column['default'] ?? '-';
    $dataid=$entry['id'];
    if(is_string($column['value'])) {
        $column['value'] = json_decode($column['value'], true);
    }
    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }
    if(!empty($column['value'])) {
        $prepearforjsjavascript=json_encode($column['value'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $column['text'] = '<code onclick="viewjson(this)" data-id="'.$dataid.'">'.$column['prefix'].Str::limit(json_encode($column['value'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),100).$column['suffix'].'</code>';
    }

@endphp


@if($column['escaped'])
{!! $column['text'] !!}
@else
{!! $column['text'] !!}
@endif
<script>
    var json_code_{{$dataid}} ='@json($column["value"])';
</script>