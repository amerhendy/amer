<!-- page_or_link -->
<?php
$language=Str::replace('_', '-', app()->getLocale());
$page_model = $field['page_model'];
$active_pages = $page_model::all();
$types= [
        'page_link'     => trans('AMER::Menu.page_link'),
        'internal_link' => trans('AMER::Menu.internal_link'),
        'external_link' => trans('AMER::Menu.external_link'),
    ];
    $target=['_self','_blank','_parent','_top','_unfencedTop'];
    $active_target=[];
    foreach ($target as $key => $value) {$active_target[$value]=$value;}
    //dd(url('link'));
?>
<div class="row form-group" data-init-function="bpFieldInitPageOrLinkElement" id="bpFieldInitPageOrLinkElement"></div>
@push('after_styles')
    @loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
    @loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap-5-theme.rtl.min.css')
@endpush
@push('after_scripts')
    @loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
    @loadScriptOnce('js/packages/select2/dist/js/i18n/'.$language.'.js')
    @loadScriptOnce('js/Amer/forms/MenuLink.js');

@loadOnce('bpFieldInitPageOrLinkElement')
<script>
    jstrans['Menu']     = {{Illuminate\Support\JS::from(trans('AMER::Menu'))}};
    const typesTypes    = {{Illuminate\Support\JS::from($types)}};
    const typesactive_pages  = {{ Illuminate\Support\Js::from($active_pages) }};
    const typesTarget   = {{Illuminate\Support\JS::from($active_target)}};
    const MenuEntry     = {{Illuminate\Support\JS::from($entry ?? [])}};
    const internal_linkHelper="{{url('link')}}";
</script>
@endLoadOnce
@endpush
<!-- page_or_link -->
