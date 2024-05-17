@php
    $related_key = $related_key ?? null;
    foreach($column['wrapper'] as $attribute => $value) {
        $column['wrapper'][$attribute] = !is_string($value) && $value instanceof \Closure ? $value($Amer, $column, $entry, $related_key) : $value ?? '';
    }
@endphp

<{{ $column['wrapper']['element'] ?? 'a' }}
@foreach(Arr::except($column['wrapper'], 'element') as $element => $value)
    {{$element}}="{{$value}}"
@endforeach
>