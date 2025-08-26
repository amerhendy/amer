<?php
$tableTrans=[
                "emptyTable"=>      trans('AMER::datatables.emptyTable'),
                "info"=>            trans('AMER::datatables.info'),
                "infoEmpty"=>       trans('AMER::datatables.infoEmpty'),
                "infoFiltered"=>    trans('AMER::datatables.infoFiltered'),
                "infoPostFix"=>     trans('AMER::datatables.infoPostFix'),
                "thousands"=>       trans('AMER::datatables.thousands'),
                "lengthMenu"=>      trans('AMER::datatables.lengthMenu'),
                "loadingRecords"=>  trans('AMER::datatables.loadingRecords'),
                "processing"=>      '<div class="spinner-grow text-primary " style="width: 3rem; height: 3rem;" role="status">
                                    <span class="sr-only">Loading...</span>
                                        </div>',
                "search"=>          "_INPUT_",
                "searchPlaceholder"=>trans('AMER::datatables.search')."...",
                "zeroRecords"=>     trans('AMER::datatables.zeroRecords'),
                "paginate"=>[
                    "first"=>      trans('AMER::datatables.paginate.first'),
                    "last"=>       trans('AMER::datatables.paginate.last'),
                    "next"=>       ">",
                    "previous"=>   "<",
                ],
                "aria"=>[
                    "sortAscending"=>   trans('AMER::datatables.aria.sortAscending'),
                    "sortDescending"=>  trans('AMER::datatables.aria.sortDescending'),
                ],
                "buttons"=>[
                    "copy"=>            "<i class='fa fa-copy'></i>",
                    "excel"=>           "<i class='fa fa-file-excel-o'></i>",
                    "csv"=>             "<i class='fa-solid fa-file-csv'></i>",
                    "pdf"=>             "<i class='fa fa-file-pdf-o'></i>",
                    "print"=>           "<i class='fa fa-print'></i>",
                    "colvis"=>          trans('AMER::datatables.export.column_visibility'),
                ],
];
?>
    @push('inject_scripts')
    jstrans['datatables']={{ Illuminate\Support\Js::from(trans('AMER::datatables')) }};
    jstrans['errors']={{ Illuminate\Support\Js::from(trans('AMER::errors')) }};
    jstrans['ajax_error_title']="{{ trans('AMER::errors.ajax_error_title') }}";
    jstrans['ajax_error_text']="{{ trans('AMER::errors.ajax_error_text') }}";
    jstrans['tableTrans']={{ Illuminate\Support\Js::from($tableTrans) }};
    @endpush
