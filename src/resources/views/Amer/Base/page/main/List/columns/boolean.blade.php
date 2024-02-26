@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    if (in_array($column['value'], [true, 1, '1'])) {
        $related_key = 1;
        
        if ( isset( $column['options'][1] ) ) {
            $column['text'] = $column['options'][1];
            $column['escaped'] = false;
        } else {
            $column['text']='<i class="fa fa-check" aria-hidden="true"></i>';
        }
    } else {
        $related_key = 0;
        if ( isset( $column['options'][0] ) ) {
            $column['text'] = $column['options'][0];
            $column['escaped'] = false;
        } else {
            $column['text'] = '<i class="fa fa-times" aria-hidden="true"></i>';
        }
    }

    $column['text'] = $column['prefix'].$column['text'].$column['suffix'];
@endphp

<span data-order="{{ $column['value'] }}">
        @if($column['escaped'])
            {!! $column['text'] !!}
        @else
            {!! $column['text'] !!}
        @endif
</span>
