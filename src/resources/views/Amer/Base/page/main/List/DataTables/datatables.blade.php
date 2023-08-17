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
    let StoragelistUrl=SlugRoute+"ListUrl";
    let StoragelistUrlTime=StoragelistUrl+"Time";
    let StorageName="DataAmerTables/"+Route;
    let DataTableCurrentDate = JSON.parse(localStorage.getItem(StorageName)) ? JSON.parse(localStorage.getItem(StorageName)) : [];
    var DefaultPageLength = {{ $DefaultPageLength }};
    let StoredPageLength = localStorage.getItem(StorageName+'_pageLength');
    if(!StoredPageLength && DataTableCurrentDate.length !== 0 && DataTableCurrentDate.length !== DefaultPageLength) {
        localStorage.removeItem(StorageName);
    }
    $oldAlerts = JSON.parse(localStorage.getItem('Amer_alerts')) ? JSON.parse(localStorage.getItem('Amer_alerts')) : {};
    $newAlerts = @json($Amer_alerts);
    Object.entries($newAlerts).forEach(function(type) {
        if(typeof $oldAlerts[type[0]] !== 'undefined') {
            type[1].forEach(function(msg) {
                $oldAlerts[type[0]].push(msg);
            });
        } else {
            $oldAlerts[type[0]] = type[1];
        }
    });
    localStorage.setItem('Amer_alerts', JSON.stringify($oldAlerts));
    @if ($Amer->getPersistentTable())
        var StorageSavedListUrl = localStorage.getItem(StoragelistUrl);
        if (StorageSavedListUrl && StorageSavedListUrl.indexOf('?') < 1) {
            var StorageSavedListUrl = false;
        } else {
            var persistentUrl = StorageSavedListUrl+'&persistent-table=true';
        }
        var arr = window.location.href.split('?');
        if (arr.length > 1 && arr[1] !== '') {
            if (window.location.search.indexOf('persistent-table=true') < 1) {
                StorageSavedListUrl = false;
            }
        }
    /////////////////////
        @if($Amer->getPersistentTableDuration())
            var StorageSavedListUrlTime = localStorage.getItem(StoragelistUrlTime);
            if (StorageSavedListUrlTime) {
                var CurrentDate = new Date();
                var SavedTime = new Date(parseInt(StorageSavedListUrlTime));
                SavedTime.setMinutes(SavedTime.getMinutes() + {{$Amer->getPersistentTableDuration()}});
                if(SavedTime > CurrentDate) {// if the save time is not expired we force the filter redirection.
                    if (StorageSavedListUrl && persistentUrl!=window.location.href) {window.location.href = persistentUrl;}
                } else {
                    StorageSavedListUrl = false;// persistent table expired, let's not redirect the user
                }
            }
        @endif
        if (StorageSavedListUrl && persistentUrl!=window.location.href) {window.location.href = persistentUrl;}
    @endif
    
    window.Amer = {
        exportButtons: JSON.parse('{!! json_encode($Amer->get('list.export_buttons')) !!}'),
        functionsToRunOnDataTablesDrawEvent: [],
        addFunctionToDataTablesDrawEventQueue: function (functionName) 
        {
            if (this.functionsToRunOnDataTablesDrawEvent.indexOf(functionName) == -1) {
                this.functionsToRunOnDataTablesDrawEvent.push(functionName);
            }
        },
        responsiveToggle: function(dt) 
        {
            $(dt.table().header()).find('th').toggleClass('all');
            dt.responsive.rebuild();
            dt.responsive.recalc();
        },
        executeFunctionByName: function(str, args) {
            var arr = str.split('.');
            var fn = window[ arr[0] ];
            for (var i = 1; i < arr.length; i++)
            {
                fn = fn[ arr[i] ]; 
            }
            fn.apply(window, args);
        },
        updateUrl : function (url) {
            let urlStart = "{{ url($Amer->getrequest()->getpathInfo()) }}";
            let urlEnd = url.replace(urlStart, '');
            urlEnd = urlEnd.replace('/search', '');
            let newUrl = urlStart + urlEnd;
            let tmpUrl = newUrl.split("?")[0],
            params_arr = [],
            queryString = (newUrl.indexOf("?") !== -1) ? newUrl.split("?")[1] : false;
            // exclude the persistent-table parameter from url
            if (queryString !== false) {
                params_arr = queryString.split("&");
                for (let i = params_arr.length - 1; i >= 0; i--) {
                    let param = params_arr[i].split("=")[0];
                    if (param === 'persistent-table') {
                        params_arr.splice(i, 1);
                    }
                }
                newUrl = params_arr.length ? tmpUrl + "?" + params_arr.join("&") : tmpUrl;
            }
            window.history.pushState({}, '', newUrl);
            localStorage.setItem(StoragelistUrl, newUrl);
        },
        dataTableConfiguration: {
            bInfo: {{ var_export($Amer->getOperationSetting('showEntryCount') ?? true) }},
            @if ($Amer->getResponsiveTable())
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal( {
                        header: function ( row ) {
                            var data = row.data();
                            return "{{trans('AMER::datatables.infoabout')}} : "+data[0];
                        }
                    }
                    ),
                    renderer: function ( api, rowIdx, columns ) {
                        var data = $.map( columns, function ( col, i ) {
                            var columnHeading = Amer.table.columns().header()[col.columnIndex];
                            if ($(columnHeading).attr('data-visible-in-modal') == 'false') {
                                return '';
                            }
                            return '<div class="row" data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                        '<div class="col-sm-3 text-wrap" style="">'+col.title.trim()+':'+'</div> '+
                                        '<div class="col-sm-9 text-wrap" style="">'+col.data+'</div>'+
                                    '</div>';
                        } ).join('');
                        return data ?
                            $('<div class="container-fluid">').append( data) :
                            false;
                    },
                }
            },
            fixedHeader: true,
            @else
            responsive: false,
            scrollX: true,
            @endif
            @if ($Amer->getPersistentTable())
            stateSave: true,
            stateSaveParams: function(settings, data) {
                localStorage.setItem(StoragelistUrlTime, data.time);
                data.columns.forEach(function(item, index) {
                    var columnHeading = Amer.table.columns().header()[index];
                    if ($(columnHeading).attr('data-visible-in-table') == 'true') {
                        return item.visible = true;
                    }
                });
            },
            @if($Amer->getPersistentTableDuration())
            stateLoadParams: function(settings, data) {
                var SavedTime = new Date(data.time);
                var CurrentDate = new Date();
                SavedTime.setMinutes(SavedTime.getMinutes() + {{$Amer->getPersistentTableDuration() ?? 120}});
                if(SavedTime < CurrentDate) {
                    if (localStorage.getItem(StoragelistUrl)) {localStorage.removeItem(StoragelistUrl);}
                    if (localStorage.getItem(StoragelistUrlTime)) {localStorage.removeItem(StoragelistUrlTime);}
                return false;
                }
            },
            @endif
            @endif
            autoWidth: false,
            pageLength: DefaultPageLength,
            lengthMenu: @json($Amer->getPageLengthMenu()),
            aaSorting: [],
            language: {
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
            },
            processing: true,
            serverSide: true,
            @if($Amer->getOperationSetting('showEntryCount') === false)
                pagingType: "simple",
            @endif
            searching: @json($Amer->getOperationSetting('searchableTable') ?? true),
            ajax: {
                "url": "{!! url($Amer->getrequest()->getpathInfo().'/search').'?'.Request::getQueryString() !!}",
                "type": "POST",
                "data": {
                    "totalEntryCount": "{{$Amer->getOperationSetting('totalEntryCount') ?? false}}"
                },
            },
            dom:
                "<'row hidden'<'col-sm-6'i><'col-sm-6 d-print-none'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-2 d-print-none '<'col-sm-12 col-md-4'l><'col-sm-0 col-md-4 text-center'B><'col-sm-12 col-md-4 'p>>",
        }
    }
  </script>
  @include(listview('buttons.export_buttons'))

  <script type="text/javascript">
    jQuery(document).ready(function($) {
      window.Amer.table = $("#AmerTable").DataTable(window.Amer.dataTableConfiguration);

      window.Amer.updateUrl(location.href);

      // move search bar
      $("#AmerTable_filter").appendTo($('#datatable_search_stack' ));
      $("#AmerTable_filter input").removeClass('form-control-sm');

      // move "showing x out of y" info to header
      @if($Amer->getSubheading())
      $('#AmerTable_info').hide();
      @else
      $("#datatable_info_stack").html($('#AmerTable_info')).css('display','inline-flex').addClass('animated fadeIn');
      @endif

      @if($Amer->getOperationSetting('resetButton') ?? true)
        // create the reset button
        var AmerTableResetButton = '<a href="{{url($Amer->getrequest()->getpathInfo())}}" class="ml-1" id="AmerTable_reset_button">{{ trans('AMER::actions.reset') }}</a>';

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
      @endif

      // move the bottom buttons before pagination
      $("#bottom_buttons").insertBefore($('#AmerTable_wrapper .row:last-child' ));

      // override ajax error message
      $.fn.dataTable.ext.errMode = 'none';
      $('#AmerTable').on('error.dt', function(e, settings, techNote, message) {
          new Noty({
              type: "error",
              text: "<strong>{{ trans('AMER::errors.ajax_error_title') }}</strong><br>{{ trans('AMER::errors.ajax_error_text') }}"
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
  </script>
@if ($Amer->get('list.detailsRow'))
  @include(Baseview('columns.inc.details_row_button-js'))
@endif