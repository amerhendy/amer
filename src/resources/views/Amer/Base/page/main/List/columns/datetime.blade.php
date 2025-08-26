{{-- localized datetime using carbon --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['format'] = $column['format'] ?? config('Amer.Amer.Carbon_dateTimeFormat');
    $column['text'] = $column['default'] ?? '-';
    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

//dd(config('Amer.Amer.Carbon_dateTimeFormat'));
    //$column['format']='A';
    //yer:Y / G g
    //month:M
    //DY: D
    //HOUR: H k
    //minute: m M
    //SECOND: S
    //MSD: A
    if(!empty($column['value'])) {
        $date = \Carbon\Carbon::parse($column['value'])
            ->locale(App::getLocale())->timeZone(config('Amer.Amer.timeZone'));
            /*
            if(\Str::contains(App::getLocale(), 'ar')){
                $date =\AmerHelper::ArabicDate($date->isoFormat('Y'), $date->isoFormat('M'), $date->isoFormat('D'), $date->isoFormat('H'), $date->isoFormat('M'));
            }else{
                $date=$date->isoFormat($column['format']);
            }*/
            $date=$date->isoFormat($column['format']);
            $date=\AmerHelper::createhtmllimitstring($date);
        $column['text'] = $column['prefix'].$date.$column['suffix'];
    }
@endphp

<span data-order="{{ $column['value'] ?? '' }}">
    @includeWhen(!empty($column['wrapper']), listview('columns.inc.wrapper_start'))
            {!! $column['text'] !!}
    @includeWhen(!empty($column['wrapper']), listview('columns.inc.wrapper_end'))
</span>
