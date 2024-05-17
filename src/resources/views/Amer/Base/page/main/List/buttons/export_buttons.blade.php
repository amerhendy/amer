@if ($Amer->exportButtons())
  <script>
    let dataTablesExportStrip = text => {
        if ( typeof text !== 'string' ) {
            return text;
        }

        return text
            .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
            .replace(/<!\-\-.*?\-\->/g, '')
            .replace(/<[^>]*>/g, '')
            .replace(/^\s+|\s+$/g, '')
            .replace(/\s+([,.;:!\?])/g, '$1')
            .replace(/\s+/g, ' ')
            .replace(/[\n|\r]/g, ' ');
    };

    let dataTablesExportFormat = {
        body: (data, row, column, node) => 
            node.querySelector('input[type*="text"]')?.value ??
            node.querySelector('input[type*="checkbox"]:not(.Amer_bulk_actions_line_checkbox)')?.checked ??
            node.querySelector('select')?.selectedOptions[0]?.value ??
            dataTablesExportStrip(data),
    };

    window.Amer.dataTableConfiguration.buttons = [
        @if($Amer->get('list.showExportButton'))
        {
            extend: 'collection',
            text: '<i class="la la-download"></i> {{ trans('AMER::datatables.export.export') }}',
            dropup: true,
            buttons: [
                {
                    name: 'copyHtml5',
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: function ( idx, data, node ) {
                            var $column = Amer.table.column( idx );
                                return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                        },
                        format: dataTablesExportFormat,
                    },
                    action: function(e, dt, button, config) {
                        Amer.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                        Amer.responsiveToggle(dt);
                    }
                },
                {
                    name: 'excelHtml5',
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: function ( idx, data, node ) {
                            var $column = Amer.table.column( idx );
                                return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                        },
                        format: dataTablesExportFormat,
                    },
                    action: function(e, dt, button, config) {
                        Amer.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                        Amer.responsiveToggle(dt);
                    }
                },
                {
                    name: 'csvHtml5',
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: function ( idx, data, node ) {
                            var $column = Amer.table.column( idx );
                                return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                        },
                        format: dataTablesExportFormat,
                    },
                    action: function(e, dt, button, config) {
                        Amer.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                        Amer.responsiveToggle(dt);
                    }
                },
                {
                    name: 'pdfHtml5',
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: function ( idx, data, node ) {
                            var $column = Amer.table.column( idx );
                                return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                        },
                        format: dataTablesExportFormat,
                    },
                    orientation: 'landscape',
                    action: function(e, dt, button, config) {
                        Amer.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                        Amer.responsiveToggle(dt);
                    }
                },
                {
                    name: 'print',
                    extend: 'print',
                    exportOptions: {
                        columns: function ( idx, data, node ) {
                            var $column = Amer.table.column( idx );
                                return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                        },
                        format: dataTablesExportFormat,
                    },
                    action: function(e, dt, button, config) {
                        Amer.responsiveToggle(dt);
                        $.fn.DataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                        Amer.responsiveToggle(dt);
                    }
                }
            ]
        }
        @endif
        @if($Amer->get('list.showTableColumnPicker'))
        ,{
            extend: 'colvis',
            text: '<i class="la la-eye-slash"></i> {{ trans('AMER::datatables.export.column_visibility') }}',
            columns: function ( idx, data, node ) {
                return $(node).attr('data-visible-in-table') == 'false' && $(node).attr('data-can-be-visible-in-table') == 'true';
            },
            dropup: true
        }
        @endif
    ];

    // move the datatable buttons in the top-right corner and make them smaller
    function moveExportButtonsToTopRight() {
      Amer.table.buttons().each(function(button) {
        if (button.node.className.indexOf('buttons-columnVisibility') == -1 && button.node.nodeName=='BUTTON')
        {
          button.node.className = button.node.className + " btn-sm";
        }
      })
      $(".dt-buttons").appendTo($('#datatable_button_stack' ));
      $('.dt-buttons').addClass('d-xs-block')
                      .addClass('d-sm-inline-block')
                      .addClass('d-md-inline-block')
                      .addClass('d-lg-inline-block');
    }

    Amer.addFunctionToDataTablesDrawEventQueue('moveExportButtonsToTopRight');
  </script>
@endif
