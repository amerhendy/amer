{{-- expand/minimize button column --}}
@if ($Amer->get('list.detailsRow'))
<span class="details-control text-center cursor-pointer m-r-5">
    <i
    class="fa fa-plus-square details-row-button cursor-pointer"
    data-entry-id="{{ $entry->getKey() }}"
    data-route="{{Route($Amer->routelist['showDetailsRow']['as'],$entry->getKey())}}"></i>
</span>
@endif
