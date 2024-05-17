(function(){
    $.ajaxSetup({ cache: true });
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    let StoragelistUrl=SlugRoute+"ListUrl";
    let StoragelistUrlTime=StoragelistUrl+"Time";
    let StorageName="DataAmerTables/"+Route;
    let DataTableCurrentDate = JSON.parse(localStorage.getItem(StorageName)) ? JSON.parse(localStorage.getItem(StorageName)) : [];
    let StoredPageLength = localStorage.getItem(StorageName+'_pageLength');
    
    removeStorageName=function(){
        if(!StoredPageLength && DataTableCurrentDate.length !== 0 && DataTableCurrentDate.length !== DefaultPageLength) {
            localStorage.removeItem(StorageName);
        }
    }
    loadAlerts=function(){
        $oldAlerts = JSON.parse(localStorage.getItem('Amer_alerts')) ? JSON.parse(localStorage.getItem('Amer_alerts')) : {};
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
    }
    resetBtn=function(){
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
    }
    SetErrorMode=function(e, settings, techNote, message){  
          new Noty({
              type: "error",
              text: "<strong>"+jstrans['ajax_error_title']+"</strong><br>"+jstrans['ajax_error_text']
          }).show();
    }
    pageLengthonLocalStorage=function(e, settings, len){
            localStorage.setItem(StorageName+'_pageLength', len);
    }
    lineButtonsAsDropdown=function(){
        Amer.functionsToRunOnDataTablesDrawEvent.forEach(function(functionName) {
            Amer.executeFunctionByName(functionName);
         });
         if ($('#AmerTable').data('has-line-buttons-as-dropdown')) {
          formatActionColumnAsDropdown();
         }
    }
    responsiveRebuild=function(){
        Amer.table.responsive.rebuild();
    }
    removeStorageName();
    loadAlerts();
    if(isEmpty(window.Amer)){
        window.Amer={};
    }
    dataTableConfiguration=function(){
        window.Amer.dataTableConfiguration={};
        window.Amer.dataTableConfiguration.bInfo= showEntryCount;
        window.Amer.dataTableConfiguration.autoWidth=false;
        window.Amer.dataTableConfiguration.pageLength= DefaultPageLength;
        window.Amer.dataTableConfiguration.lengthMenu= lengthMenu;
        window.Amer.dataTableConfiguration.aaSorting= [];
        window.Amer.dataTableConfiguration.processing= true;
        //window.Amer.dataTableConfiguration.serverSide= true;
        window.Amer.dataTableConfiguration.searching= searchableTable;
        window.Amer.dataTableConfiguration.dom="<'row hidden'<'col-sm-6'i><'col-sm-6 d-print-none'f>>" +
                                                "<'row'<'col-sm-12'tr>>" +
                                                "<'row mt-2 d-print-none '<'col-sm-12 col-md-4'l><'col-sm-0 col-md-4 text-center'B><'col-sm-12 col-md-4 'p>>";
        if(showEntryCount == false){
            window.Amer.dataTableConfiguration.pagingType= "simple";
        }
        window.Amer.exportButtons=exportButtons;
        window.Amer.functionsToRunOnDataTablesDrawEvent=[];
    }
    dataTableConfiguration();
    window.Amer.dataTableConfiguration.ajax={};
    window.Amer.dataTableConfiguration.ajax.url=searchQueryRoute;
    window.Amer.dataTableConfiguration.ajax.type='POST';
    window.Amer.dataTableConfiguration.ajax.data=totalEntryCount;

    
    
    
    window.Amer.addFunctionToDataTablesDrawEventQueue=function (functionName) 
    {
        if (this.functionsToRunOnDataTablesDrawEvent.indexOf(functionName) == -1) {
            this.functionsToRunOnDataTablesDrawEvent.push(functionName);
        }
    }
    window.Amer.responsiveToggle=function(dt) 
        {
            $(dt.table().header()).find('th').toggleClass('all');
            dt.responsive.rebuild();
            dt.responsive.recalc();
        }
    window.Amer.executeFunctionByName=function(str, args) {
        var arr = str.split('.');
        var fn = window[ arr[0] ];
        for (var i = 1; i < arr.length; i++)
        {
            fn = fn[ arr[i] ]; 
        }
        fn.apply(window, args);
    }
    window.Amer.updateUrl=function (url) {
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
    }
    if (getPersistentTable){
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
        if(getPersistentTableDuration){
            var StorageSavedListUrlTime = localStorage.getItem(StoragelistUrlTime);
            if (StorageSavedListUrlTime) {
                var CurrentDate = new Date();
                var SavedTime = new Date(parseInt(StorageSavedListUrlTime));
                SavedTime.setMinutes(SavedTime.getMinutes() + getPersistentTableDuration);
                if(SavedTime > CurrentDate) {// if the save time is not expired we force the filter redirection.
                    if (StorageSavedListUrl && persistentUrl!=window.location.href) {window.location.href = persistentUrl;}
                } else {
                    StorageSavedListUrl = false;// persistent table expired, let's not redirect the user
                }
            }
        }
        if (StorageSavedListUrl && persistentUrl!=window.location.href) {window.location.href = persistentUrl;}
    }
    if(getResponsiveTable){
        window.Amer.dataTableConfiguration.responsive={};
        window.Amer.dataTableConfiguration.responsive.details={
            display: $.fn.dataTable.Responsive.display.modal( {
                header: function ( row ) {
                    var data = row.data();
                    return jstrans['infoabout']+" : "+data[0];
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
        };
        window.Amer.dataTableConfiguration.fixedHeader= true;
    }else{
        window.Amer.dataTableConfiguration.responsive= false;
        window.Amer.dataTableConfiguration.scrollX=true;
    }
    if(getPersistentTable){
        window.Amer.dataTableConfiguration.stateSave=true;
        window.Amer.dataTableConfiguration.stateSaveParams= function(settings, data) {
                localStorage.setItem(StoragelistUrlTime, data.time);
                data.columns.forEach(function(item, index) {
                    if(Amer.table){
                        var columnHeading = Amer.table.columns().header()[index];
                    if ($(columnHeading).attr('data-visible-in-table') == 'true') {
                        return item.visible = true;
                    }
                    }
                    
                });
            };
        if(getPersistentTableDuration){
            window.Amer.dataTableConfiguration.stateLoadParams= function(settings, data) {
                var SavedTime = new Date(data.time);
                var CurrentDate = new Date();
                SavedTime.setMinutes(SavedTime.getMinutes() + getPersistentTableDuration);
                if(SavedTime < CurrentDate) {
                    if (localStorage.getItem(StoragelistUrl)) {localStorage.removeItem(StoragelistUrl);}
                    if (localStorage.getItem(StoragelistUrlTime)) {localStorage.removeItem(StoragelistUrlTime);}
                return false;
                }
            };
        }
    }
    
    function isEmpty(value) {
        for (let prop in value) {
          if (value.hasOwnProperty(prop)) return false;
        }
        return true;   
    }
    window.Amer.table = $("#AmerTable").DataTable(window.Amer.dataTableConfiguration);
    window.Amer.updateUrl(location.href);
    $("#AmerTable_filter").appendTo($('#datatable_search_stack' ));
    $("#AmerTable_filter input").removeClass('form-control-sm');
    if(getSubheading){
        $('#AmerTable_info').hide();
    }else{
        $("#datatable_info_stack").html($('#AmerTable_info')).css('display','inline-flex').addClass('animated fadeIn');
    }
    resetBtn();
    $("#bottom_buttons").insertBefore($('#AmerTable_wrapper .row:last-child' ));
    
    $.fn.dataTable.ext.errMode = 'none';
    $('#AmerTable').on('error.dt', function(e, settings, techNote, message) {
        SetErrorMode(e, settings, techNote, message);
    });
    $('#AmerTable').on( 'length.dt', function ( e, settings, len ) {
        pageLengthonLocalStorage(e, settings, len);
    });
    $.ajaxPrefilter(function(options, originalOptions, xhr) {
        var token = $('meta[name="csrf_token"]').attr('content');
        if (token) {
            return xhr.setRequestHeader('X-XSRF-TOKEN', token);
        }
    });
    $('#AmerTable').on( 'page.dt', function () {
        localStorage.setItem('page_changed', true);
    });
    $('#AmerTable').on( 'draw.dt',   function () {
        lineButtonsAsDropdown();
     }).dataTable();
     $('#AmerTable').on( 'column-visibility.dt',   function (event) {
        responsiveRebuild();
     } ).dataTable();
     if (getResponsiveTable){
        Amer.table.on( 'responsive-resize', function ( e, datatable, columns ) {
            if (Amer.table.responsive.hasHidden()) {
              $("#AmerTable").removeClass('has-hidden-columns').addClass('has-hidden-columns');
             } else {
              $("#AmerTable").removeClass('has-hidden-columns');
             }
        });
     }else{
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
     }
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
                actionCell.prepend('<a class="btn btn-sm px-2 py-1 btn-outline-primary dropdown-toggle actions-buttons-column" href="#" data-toggle="dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">'+jstrans['actionsactions']+'</a>');
            });
        }
    }
    console.log(window.Amer);
})(jQuery)