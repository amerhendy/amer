@foreach ($fields as $field)
    @php
        if(isset($field['view_namespace'])){
            $fieldsViewNamespace = $field['view_namespace'];
        }else{
            if(\Str::of($field['type'])->contains('::')){
                $fieldsViewNamespace =\Str::of($field['type'])->before('::').'::';
                $field['type']=\Str::of($field['type'])->after('::');
            }else{
                $fieldsViewNamespace = 'Amer::Base.page.main.Forms.fields';
            }
        }
    @endphp
    
    @include($Amer->getFirstFieldView($field['type'], $fieldsViewNamespace ?? false),$field)
@endforeach