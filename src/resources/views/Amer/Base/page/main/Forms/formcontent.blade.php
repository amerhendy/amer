@isset($fields)
@foreach($fields as $field)
@php
if($field['type'] !=='text'){
        
        //dd($field['type']);
}
@endphp
        @include(fieldview('inc.wrapper_start'))
        @if(!Str::contains($field['type'],'::'))
                @include(fieldview($field['type']))
        @else
                @include($field['type'])
        @endif
        @include(fieldview('inc.wrapper_end'))
@endforeach
@endisset
@push('after_scripts')
<script>
        document.AmerField={};
        formvalidation();
        function formvalidation(){
                jQuery(document).ready(function() {
                        var inputs = document.forms["form"].getElementsByTagName("input");
                        var selects = document.forms["form"].getElementsByTagName("select");
                        var textarea =document.forms["form"].getElementsByTagName("textarea");
                        $.each(inputs,function(index,element){
                                if($(element).attr('type') == 'text'){
                                        //console.log($(element).attributes());
                                }
                        });
                        //console.log(forms);
                });
        }
</script>
@endpush