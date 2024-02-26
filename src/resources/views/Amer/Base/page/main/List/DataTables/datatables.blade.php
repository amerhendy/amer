@php
    // as it is possible that we can be redirected with persistent table we save the alerts in a variable
    // and flush them from session, so we will get them later from localStorage.
    $Amer_alerts = \Alert::getMessages();
    \Alert::flush();
    //dd($Amer->routelist['showDetailsRow']);
 @endphp
  <script>    
    let Route="{{$Amer->getRoute()}}";
    let SlugRoute="{{ Str::slug($Amer->getRoute()) }}";
    var DefaultPageLength = {{ $DefaultPageLength }};
    let getPersistentTable={{$Amer->getPersistentTable()}};
    let getResponsiveTable={{$Amer->getResponsiveTable()}};
    let getPersistentTableDuration="{{$Amer->getPersistentTableDuration()}}";
    $newAlerts = @json($Amer_alerts);
    let exportButtons= JSON.parse('{!! json_encode($Amer->get('list.export_buttons')) !!}');
    let urlStart = "{{ url($Amer->getrequest()->getpathInfo()) }}";
    let showEntryCount={{ var_export($Amer->getOperationSetting('showEntryCount') ?? true) }};
    let lengthMenu=`@json($Amer->getPageLengthMenu())`;
    let searchableTable=@json($Amer->getOperationSetting('searchableTable') ?? true);
    let searchQueryRoute=`{!! url($Amer->getrequest()->getpathInfo().'/search').'?'.Request::getQueryString() !!}`;
    let totalEntryCount="{{$Amer->getOperationSetting('totalEntryCount') ?? false}}";
    let getSubheading={{$Amer->getSubheading() ?? true}};
    let resetButton={{$Amer->getOperationSetting('resetButton') ?? true}};
    jstrans['infoabout']="{{trans('AMER::datatables.infoabout')}}";
    jstrans['actionsReset']="{{ trans('AMER::actions.reset') }}";
    jstrans['actionsactions']="{{ trans('AMER::actions.actions') }}";
    jstrans['ajax_error_title']="{{ trans('AMER::errors.ajax_error_title') }}";
    jstrans['ajax_error_text']="{{ trans('AMER::errors.ajax_error_text') }}";
    
    jstrans['tableTrans']={
                "emptyTable":     "{{ trans('AMER::datatables.emptyTable') }}",
                "info":           "{{ trans('AMER::datatables.info') }}",
                "infoEmpty":      "{{ trans('AMER::datatables.infoEmpty') }}",
                "infoFiltered":   "{{ trans('AMER::datatables.infoFiltered') }}",
                "infoPostFix":    "{{ trans('AMER::datatables.infoPostFix') }}",
                "thousands":      "{{ trans('AMER::datatables.thousands') }}",
                "lengthMenu":     "{{ trans('AMER::datatables.lengthMenu') }}",
                "loadingRecords": "{{ trans('AMER::datatables.loadingRecords') }}",
                //"processing":     "<img src='{{ asset('images/nsscww.gif') }}' class='Loading'>",
                "processing":`<div class="spinner-grow text-primary " style="width: 3rem; height: 3rem;" role="status">
                                    <span class="sr-only">Loading...</span>
                                        </div>`,
                "search": "_INPUT_",
                "searchPlaceholder": "{{ trans('AMER::datatables.search') }}...",
                "zeroRecords":    "{{ trans('AMER::datatables.zeroRecords') }}",
                "paginate": {
                    "first":      "{{ trans('AMER::datatables.paginate.first') }}",
                    "last":       "{{ trans('AMER::datatables.paginate.last') }}",
                    "next":       ">",
                    "previous":   "<"
                },
                "aria": {
                    "sortAscending":  "{{ trans('AMER::datatables.aria.sortAscending') }}",
                    "sortDescending": "{{ trans('AMER::datatables.aria.sortDescending') }}"
                },
                "buttons": {
                    "copy":   "<i class='fa fa-copy'></i>",
                    "excel":  "<i class='fa fa-file-excel-o'></i>",
                    "csv":    "<i class='fa-solid fa-file-csv'></i>",
                    "pdf":    "<i class='fa fa-file-pdf-o'></i>",
                    "print":  "<i class='fa fa-print'></i>",
                    "colvis": "{{ trans('AMER::datatables.export.column_visibility') }}"
                },
            };
  </script>
  @include(listview('buttons.export_buttons'))

  <script type="text/javascript">
    /*
    jQuery(document).ready(function($) {
        
      //window.Amer.table = $("#AmerTable").DataTable(window.Amer.dataTableConfiguration);
      //window.Amer.updateUrl(location.href);

      // move search bar
      $("#AmerTable_filter").appendTo($('#datatable_search_stack' ));
      $("#AmerTable_filter input").removeClass('form-control-sm');

      // move "showing x out of y" info to header
      @if($Amer->getSubheading())
      $('#AmerTable_info').hide();
      @else
      $("#datatable_info_stack").html($('#AmerTable_info')).css('display','inline-flex').addClass('animated fadeIn');
      @endif
      
      if(resetButton){
        // create the reset button
        var AmerTableResetButton = '<a href="'+urlStart+'" class="ml-1" id="AmerTable_reset_button">'+jstrans['actionsReset']+'</a>';

        $('#datatable_info_stack').append(AmerTableResetButton);

          // when clicking in reset button we clear the localStorage for datatables.
        $('#AmerTable_reset_button').on('click', function() {

          //clear the filters
          if (localStorage.getItem(StoragelistUrl)) {
              localStorage.removeItem(StoragelistUrl);
          }
          if (localStorage.getItem(StoragelistUrlTime)) {
              localStorage.removeItem(StoragelistUrlTime);
          }

          //clear the table sorting/ordering/visibility
          if(localStorage.getItem(StorageName)) {
              localStorage.removeItem(StorageName);
          }
        });
    }

      /// move the bottom buttons before pagination
      $("#bottom_buttons").insertBefore($('#AmerTable_wrapper .row:last-child' ));

      // override ajax error message
      //$.fn.dataTable.ext.errMode = 'none';
      $('#AmerTable').on('error.dt', function(e, settings, techNote, message) {
          new Noty({
              type: "error",
              text: "<strong>"+jstrans['ajax_error_title']+"</strong><br>"+jstrans['ajax_error_text']
          }).show();
      });

        // when changing page length in datatables, save it into localStorage
        // so in next requests we know if the length changed by user
        // or by developer in the controller.
        $('#AmerTable').on( 'length.dt', function ( e, settings, len ) {
            localStorage.setItem(StorageName+'_pageLength', len);
        });

        // make sure AJAX requests include XSRF token
        $.ajaxPrefilter(function(options, originalOptions, xhr) {
            var token = $('meta[name="csrf_token"]').attr('content');
            if (token) {
                return xhr.setRequestHeader('X-XSRF-TOKEN', token);
            }
        });
        $('#AmerTable').on( 'page.dt', function () {
            localStorage.setItem('page_changed', true);
        });

      // on DataTable draw event run all functions in the queue
      // (eg. delete and details_row buttons add functions to this queue)
      $('#AmerTable').on( 'draw.dt',   function () {
         Amer.functionsToRunOnDataTablesDrawEvent.forEach(function(functionName) {
            Amer.executeFunctionByName(functionName);
         });
         if ($('#AmerTable').data('has-line-buttons-as-dropdown')) {
          formatActionColumnAsDropdown();
         }
      }).dataTable();

      // when datatables-colvis (column visibility) is toggled
      // rebuild the datatable using the datatable-responsive plugin
      $('#AmerTable').on( 'column-visibility.dt',   function (event) {
         Amer.table.responsive.rebuild();
      } ).dataTable();

      @if ($Amer->getResponsiveTable())
        // when columns are hidden by reponsive plugin,
        // the table should have the has-hidden-columns class
        Amer.table.on( 'responsive-resize', function ( e, datatable, columns ) {
            if (Amer.table.responsive.hasHidden()) {
              $("#AmerTable").removeClass('has-hidden-columns').addClass('has-hidden-columns');
             } else {
              $("#AmerTable").removeClass('has-hidden-columns');
             }
        } );
      @else
        // make sure the column headings have the same width as the actual columns
        // after the user manually resizes the window
        var resizeTimer;
        function resizeAmerTableColumnWidths() {
          clearTimeout(resizeTimer);
          resizeTimer = setTimeout(function() {
            // Run code here, resizing has "stopped"
            Amer.table.columns.adjust();
          }, 250);
        }
        $(window).on('resize', function(e) {
          resizeAmerTableColumnWidths();
        });
        $('.sidebar-toggler').click(function() {
          resizeAmerTableColumnWidths();
        });
      @endif

    });

    function formatActionColumnAsDropdown() {
        // Get action column
        const actionColumnIndex = $('#AmerTable').find('th[data-action-column=true]').index();
        if (actionColumnIndex !== -1) {
            $('#AmerTable tr').each(function (i, tr) {
                const actionCell = $(tr).find('td').eq(actionColumnIndex);
                const actionButtons = $(actionCell).find('a.btn.btn-link');
                // Wrap the cell with the component needed for the dropdown
                actionCell.wrapInner('<div class="nav-item dropdown"></div>');
                actionCell.wrapInner('<div class="dropdown-menu dropdown-menu-left"></div>');
                // Prepare buttons as dropdown items
                actionButtons.map((index, action) => {
                    $(action).addClass('dropdown-item').removeClass('btn btn-sm btn-link');
                    $(action).find('i').addClass('me-2 text-primary');
                });
                actionCell.prepend('<a class="btn btn-sm px-2 py-1 btn-outline-primary dropdown-toggle actions-buttons-column" href="#" data-toggle="dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">{{ trans('AMER::actions.actions') }}</a>');
            });
        }
    }
    var myModal = document.getElementsByClassName('modal')
    $.each(myModal,function(k,v){
        $(v)[0].addEventListener('shown.bs.modal', function () {
            alert("SD");
        })
    })
    
*/
  </script>
@if ($Amer->get('list.detailsRow'))
  @include(Baseview('columns.inc.details_row_button-js'))
@endif
@loadScriptOnce("js/datatables.js")