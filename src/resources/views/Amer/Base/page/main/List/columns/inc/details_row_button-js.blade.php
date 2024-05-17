{{-- expand/minimize button column --}}
@if ($Amer->get('list.detailsRow'))
  <script>
   
    if (typeof registerDetailsRowButtonAction != 'function') {
      function registerDetailsRowButtonAction() {
            // Remove any previously registered event handlers from draw.dt event callback
            $('#AmerTable tbody').off('click', 'td .details-row-button');
            // Make sure the ajaxDatatables rows also have the correct classes
            $('#AmerTable tbody td .details-row-button').parent('td')
              .removeClass('details-control').addClass('details-control')
              .removeClass('text-center').addClass('text-center')
              .removeClass('cursor-pointer').addClass('cursor-pointer');

            // Add event listener for opening and closing details
            $('#AmerTable tbody td .details-control').on('click', function (e) {
              e.stopPropagation();

                var tr = $(this).closest('tr');
                var btn = $(this).find('.details-row-button');
                var row = Amer.table.row( tr );

                if (row.child.isShown()) {
                    // This row is already open - close it
                    btn.removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
                    $('div.table_row_slider', row.child()).slideUp( function () {
                        row.child.hide();
                        tr.removeClass('shown');
                    } );
                } else {
                    // Open this row
                    btn.removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
                    // Get the details with ajax 
                    var did=btn.data('entry-id');
                    var mlink=btn.data('route');
                    $.ajax({
                      url: mlink,
                      type: 'GET',
                    })
                    .done(function(data) {
                      row.child("<div class='table_row_slider'>" + data + "</div>", 'no-padding').show();
                      tr.addClass('shown');
                      $('div.table_row_slider', row.child()).slideDown();
                    })
                    .fail(function(data) {
                      row.child("<div class='table_row_slider'>{{ trans('AMER::actions.details_row_loading_error') }}</div>").show();
                      tr.addClass('shown');
                      $('div.table_row_slider', row.child()).slideDown();
                    });
                }
            } );
          }
      }

    // make it so that the function above is run after each DataTable draw event
    // otherwise details_row buttons wouldn't work on subsequent pages (page 2, page 17, etc)
    Amer.addFunctionToDataTablesDrawEventQueue('registerDetailsRowButtonAction');
  </script>
@endif
