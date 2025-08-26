{{-- regular object attribute --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['limit'] = $column['limit'] ?? 32;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['text'] = $column['default'] ?? '-';
    $column['replace'] =$column['replace'] ?? false;
    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    if(is_array($column['value'])) {
        $column['value'] = json_encode($column['value']);
    }
    if(!empty($column['value'])) {
        $text=\AmerHelper::createhtmllimitstring($column['value']);
        $column['text'] = $column['prefix'].$text.$column['suffix'];
    }
    if($column['replace']){
        foreach ($column['replace'] as $key => $value) {
            $column['text']=\Str::replace($key, $value, $column['text']);
        }
    }
@endphp

<span>
    @includeWhen(!empty($column['wrapper']), listview('columns.inc.wrapper_start'))
            {!! $column['text'] !!}
    @includeWhen(!empty($column['wrapper']), listview('columns.inc.wrapper_end'))
</span>
