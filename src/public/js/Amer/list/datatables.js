(function(){
    
    window.Amer.dataTableConfiguration={};
    window.Amer.functionsToRunOnDataTablesDrawEvent=new Array();
    $.ajaxSetup({ cache: true ,headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajaxPrefilter(function(options, originalOptions, xhr) {
        var token = $('meta[name="csrf_token"]').attr('content');
        if (token) {
            return xhr.setRequestHeader('X-XSRF-TOKEN', token);
        }
    });
    let StoragelistUrl=SlugRoute+"ListUrl";
    let StoragelistUrlTime=StoragelistUrl+"Time";
    let StorageName="DataAmerTables/"+Route;
    let DataTableCurrentDate = JSON.parse(localStorage.getItem(StorageName)) ? JSON.parse(localStorage.getItem(StorageName)) : [];
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
    window.Amer.moveExportButtonsToTopRight=()=>{
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
    };
    window.Amer.registerDetailsRowButtonAction=function() {
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
                    type: 'post',
                    accepts:'application/json, text/javascript, */*; q=0.01',
                    dataType:"json",
                    contentType:'application/x-www-form-urlencoded;charset=UTF-8',
                })
                .done(function(data) {
                  row.child("<div class='table_row_slider'>" + data + "</div>", 'no-padding').show();
                  tr.addClass('shown');
                  $('div.table_row_slider', row.child()).slideDown();
                })
                .fail(function(data) {
                  row.child("<div class='table_row_slider'>"+jstrans['ajax_error_text']+"</div>").show();
                  tr.addClass('shown');
                  $('div.table_row_slider', row.child()).slideDown();
                });
            }
        } );
    }
    window.Amer.isEmpty=(value)=>{
        for (let prop in value) {
          if (value.hasOwnProperty(prop)) return false;
        }
        return true;   
    };
    window.Amer.readmore=(e,type)=>{
        mainobject=$(e).parent();
        td=$(mainobject).parent()
        tdchilds=$(td).children();
        for(i=0;i<tdchilds.length;i++){
            if($(tdchilds[i]).attr('id') == type){
                targetdiv=$(tdchilds[i]);
            }
        }
        $(targetdiv).css('display','block')
        $(mainobject).css('display','none')
    };
    window.Amer.loadAlerts=()=>{
        $oldAlerts = JSON.parse(localStorage.getItem('Amer_alerts')) ? JSON.parse(localStorage.getItem('Amer_alerts')) : {};
        Object.entries(newAlerts).forEach(function(type) {
            if(typeof $oldAlerts[type[0]] !== 'undefined') {
                type[1].forEach(function(msg) {
                    $oldAlerts[type[0]].push(msg);
                });
            } else {
                $oldAlerts[type[0]] = type[1];
            }
        });
        localStorage.setItem('Amer_alerts', JSON.stringify($oldAlerts));
    };
    window.Amer.PageLength=(action=null,len=null)=>{
        if(action == 'get'){
            if(localStorage.getItem(StorageName+'_pageLength') == 'null'){
                window.Amer.PageLength('set',DefaultPageLength);
                return DefaultPageLength;
            }
            return localStorage.getItem(StorageName+'_pageLength');
        }
        if(action == 'set'){
            if(len == null){
                len=DefaultPageLength;
            }
            if(len == 0){
                len=localStorage.getItem(StorageName+'_pageLength');
            }
            localStorage.setItem(StorageName+'_pageLength', len);
        }
        
    }
    window.Amer.removeStorageName=()=>{
        if(!window.Amer.PageLength('get') && DataTableCurrentDate.length !== 0 && DataTableCurrentDate.length !== DefaultPageLength) {
            localStorage.removeItem(StorageName);
        }
    };
    window.Amer.formatActionColumnAsDropdown=()=>{
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
                var $prepend=`<a 
                                class="btn btn-sm px-2 py-1 btn-outline-primary dropdown-toggle actions-buttons-column" 
                                href="#" 
                                data-toggle="dropdown" 
                                data-bs-toggle="dropdown" 
                                data-bs-auto-close="outside" 
                                aria-expanded="false">jstrans['actionsactions']
                                </a>`;
                actionCell.prepend($prepend);
            });
        }
    }
    window.Amer.resetBtn=function(){
        if(resetButton){
            // create the reset button
            var AmerTableResetButton=`<div class="col-sm"><a href="${urlStart}" class="ml-1" id="AmerTable_reset_button"><i class="fa fa-refresh" aria-hidden="true"></i></a></div>`;
            //AmerTableResetButton = `<a href="${urlStart}" class="ml-1" id="AmerTable_reset_button"><i class="fa fa-refresh" aria-hidden="true"></i></a>`;
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
    window.Amer.SetErrorMode=(e, settings, techNote, message)=>{
        new Noty({
            type: "error",
            text: "<strong>"+jstrans.ajax_error_title+"</strong><br>"+jstrans.ajax_error_text
        }).show();
    };
    window.Amer.executeFunctionByName=(functionName, context, args)=>{
        var args = Array.prototype.slice.call(arguments, 2);
        var namespaces = functionName.split(".");
        var func = namespaces.pop();
        for(var i = 0; i < namespaces.length; i++) {
          context = context[namespaces[i]];
        }
        return context[func].apply(context, args);
        if(typeof str !== undefined){
            fn=str;
            return;
        }else{
        var arr = str.split('.');
        var fn = window[ arr[0] ];
        for (var i = 1; i < arr.length; i++)
        {
            fn = fn[ arr[i] ]; 
        }
    }
        
        fn.apply(window, args);
    }
    window.Amer.lineButtonsAsDropdown=function(){
        Amer.functionsToRunOnDataTablesDrawEvent.forEach(function(functionName) {
            Amer.executeFunctionByName(functionName,window.Amer);
         });
         if ($('#AmerTable').data('has-line-buttons-as-dropdown')) {
            window.Amer.formatActionColumnAsDropdown();
         }
    }
    window.Amer.responsiveRebuild=function(){
        Amer.table.responsive.rebuild();
    }
    window.Amer.setDom=()=>{
        return "<'row hidden'<'col-sm-6'i><'col-sm-6 d-print-none'f>>" +
                                                "<'row'<'col-sm-12'tr>>" +
                                                "<'row mt-2 d-print-none '<'col-sm-12 col-md-4'l><'col-sm-0 col-md-4 text-center'B><'col-sm-12 col-md-4 'p>>";
    };
    window.Amer.unbindbtns=function(element,action){
        $(`[data-button-type=${element}]`).unbind(action);  
     }
    window.Amer.exportbtns=()=>{
        var btns=[];
        var copyHtml5={
            name: 'copyHtml5',
            text: `<i class="fa-solid fa-copy"></i> Copy`,
            extend: 'copyHtml5',
            exportOptions: {
                columns: function ( idx, data, node ) {
                    var $column = Amer.table.column( idx );
                        return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                },
                format: dataTablesExportFormat,
            },
            action: function(e, dt, button, config) {
                window.Amer.responsiveToggle(dt);
                $.fn.DataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                window.Amer.responsiveToggle(dt);
            }
        };
        var excelHtml5={
            name: 'excelHtml5',
            extend: 'excelHtml5',
            text: `<i class="fa-regular fa-file-excel"></i> Excel`,
            exportOptions: {
                columns: function ( idx, data, node ) {
                    var $column = Amer.table.column( idx );
                        return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                },
                format: dataTablesExportFormat,
            },
            action: function(e, dt, button, config) {
                window.Amer.responsiveToggle(dt);
                $.fn.DataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                window.Amer.responsiveToggle(dt);
            }
        };
        var csvHtml5={
            name: 'csvHtml5',
            extend: 'csvHtml5',
            text: `<i class="fa-solid fa-file-csv"></i> CSV`,
            exportOptions: {
                columns: function ( idx, data, node ) {
                    var $column = Amer.table.column( idx );
                        return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                },
                format: dataTablesExportFormat,
            },
            action: function(e, dt, button, config) {
                window.Amer.responsiveToggle(dt);
                $.fn.DataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                window.Amer.responsiveToggle(dt);
            }
        };
        var pdfHtml5={
            name: 'pdfHtml5',
            extend: 'pdfHtml5',
            text: `<i class="fa-solid fa-file-pdf"></i> PDF`,
            exportOptions: {
                columns: function ( idx, data, node ) {
                    var $column = Amer.table.column( idx );
                        return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                },
                format: dataTablesExportFormat,
            },
            orientation: 'landscape',
            action: function(e, dt, button, config) {
                window.Amer.responsiveToggle(dt);
                $.fn.DataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                window.Amer.responsiveToggle(dt);
            }
        };
        var printHtml5={
            name: 'print',
            extend: 'print',
            text: `<i class="fa-solid fa-print"></i> Print`,
            exportOptions: {
                columns: function ( idx, data, node ) {
                    var $column = Amer.table.column( idx );
                        return  ($column.visible() && $(node).attr('data-visible-in-export') != 'false') || $(node).attr('data-force-export') == 'true';
                },
                format: dataTablesExportFormat,
            },
            action: function(e, dt, button, config) {
                window.Amer.responsiveToggle(dt);
                $.fn.DataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                window.Amer.responsiveToggle(dt);
            }
        };
        btns.push(copyHtml5);
        btns.push(excelHtml5);
        btns.push(csvHtml5);
        btns.push(pdfHtml5);
        btns.push(printHtml5);
        var ops=new Array();
        btns={
            extend: 'collection',
            text: `<i class="la la-download"></i> ${jstrans['datatables']['export']['export']}`,
            dropup: true,
            buttons:btns,
        };
        ops.push({
            extend: 'colvis',
            text: `<i class="la la-eye-slash"></i> ${jstrans['datatables']['export']['column_visibility']}`,
            columns: function ( idx, data, node ) {
                return $(node).attr('data-visible-in-table') == 'false' && $(node).attr('data-can-be-visible-in-table') == 'true';
            },
            dropup: true
        });
        ops.push(btns);
        return ops;
    }
    window.Amer.prepeareUrl=(url)=>{
        var start=0;
        var length=DefaultPageLength;
        var search='';
        var newurl='';
        newurl+=url;
        //newurl+="start="+start;
        //newurl+="&length="+length;
        return newurl;
    };
    window.Amer.updateUrl=(url)=>{
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
    window.Amer.addFunctionToDataTablesDrawEventQueue=(functionName)=>{
        if (Amer.functionsToRunOnDataTablesDrawEvent.indexOf(functionName) == -1) {
            Amer.functionsToRunOnDataTablesDrawEvent.push(functionName);
            
        }
    };
    window.Amer.responsiveToggle=function(dt) 
        {
            $(dt.table().header()).find('th').toggleClass('all');
            dt.responsive.rebuild();
            dt.responsive.recalc();
    };
    window.Amer.setDataTableResponsiveTable=()=>{
        return {
            details:{
                display:$.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        return window.Amer.renderResponsiveTableHeader(row.data());
                    }
                }),
                renderer:function ( api, rowIdx, columns ) {
                    var data = $.map( columns, function ( col, i ) {
                        var columnHeading = Amer.table.columns().header()[col.columnIndex];
                        if ($(columnHeading).attr('data-visible-in-modal') == 'false') {
                            return '';
                        }
                        if(col.title.includes('Amer_bulk_actions_checkbox')){
                            col.title=split_text(col.title,' </span>');
                            col.title=col.title[1];
                        }
                        if(col.data.includes('Amer_bulk_actions_checkbox')){
                            if(col.data.includes('<span class="d-inline-flex">')){
                                col.data=split_text(col.data,' <span class="d-inline-flex">');
                                col.data=split_text(col.data[1],'</span>');    
                                col.data=col.data[0].replace(/^\s+|\s+$/g, "");
                            }else{
                                col.data=split_text(col.data,'<span>');
                                //col.data=col.data[1];
                                col.data=col.data[1].replace(/^\s+|\s+$/g, "");
                            }
                        }
                        return '<div class="row" data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                    '<div class="col-sm-3 text-wrap" style="">'+col.title.replace(/^\s+|\s+$/g, "").trim()+':'+'</div> '+
                                    '<div class="col-sm-9 text-wrap" style="">'+col.data.trim()+'</div>'+
                                '</div>';
                    } ).join('');
                    return data ?
                    $('<div class="container-fluid">').append( data) :
                    false;
                },
            }
        };
    };3
    window.Amer.renderResponsiveTableHeader=(data)=>{
        var title=data[0];
        if(title.includes('Amer_bulk_actions_checkbox') === false){
            return jstrans.datatables.infoabout+" : "+title;
        }
        if(title.includes('shortdata')){
            title=split_text(title,'<div id="shortdata">');
            title=split_text(title[1],'<span');
            title=title[0].replace(/^\s+|\s+$/g, "");
            return jstrans.datatables.infoabout+" : "+title;
        }else{
            title=split_text(title,'<span class="d-inline-flex">');
            title=split_text(title[1],'</span>');
            title=title[0].replace(/^\s+|\s+$/g, "");
            return jstrans.datatables.infoabout+" : "+title;
        }
    };
    window.Amer.renderResponsiveTableBody=(api, rowIdx, columns)=>{
        var data = $.map( columns, function ( col, i ) {
            var columnHeading = Amer.table.columns().header()[col.columnIndex];
            if ($(columnHeading).attr('data-visible-in-modal') !== 'true') {return '';}
            if(col.title.includes('Amer_bulk_actions_checkbox')){
                col.title=split_text(col.title,' </span>');
                col.title=col.title[1];
            }
            if(col.data.includes('Amer_bulk_actions_checkbox')){
                if(col.data.includes('<span class="d-inline-flex">')){
                    col.data=split_text(col.data,' <span class="d-inline-flex">');
                    col.data=split_text(col.data[1],'</span>');
                    col.data=col.data[0].replace(/^\s+|\s+$/g, "");
                }else{
                    col.data=split_text(col.data,'<span>');
                    col.data=col.data[1].replace(/^\s+|\s+$/g, "");
                    //console.log(col.data[0]);
                    
                }
            }
            return '<div class="row" data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<div class="col-sm-3 text-wrap" style="">'+col.title.replace(/^\s+|\s+$/g, "").trim()+':'+'</div> '+
                                '<div class="col-sm-9 text-wrap" style="">'+col.data.trim()+'</div>'+
                            '</div>';
        } ).join(' ');
        return data;
    };
    window.Amer.setDataTableConfiguration=()=>{
        window.Amer.dataTableConfiguration.bInfo= showEntryCount;
        window.Amer.dataTableConfiguration.autoWidth=false;
        window.Amer.dataTableConfiguration.pageLength= parseInt(window.Amer.PageLength('get'));
        window.Amer.dataTableConfiguration.lengthMenu= JSON.parse(lengthMenu);
        window.Amer.dataTableConfiguration.aaSorting= [];
        window.Amer.dataTableConfiguration.processing= true;
        window.Amer.dataTableConfiguration.serverSide= true;
        window.Amer.dataTableConfiguration.searching= searchableTable;
        window.Amer.dataTableConfiguration.dom=window.Amer.setDom();
        window.Amer.dataTableConfiguration.drawCallback=function (settings) {            
            var api = this.api();
            //console.log(this.api().draw());
            // Output the data for the visible rows to the browser's console
            //console.log(api.rows({ page: 'current' }).data());
        }
        window.Amer.dataTableConfiguration.ajax=window.Amer.setDataTableAjax();
        if(getResponsiveTable){
            window.Amer.dataTableConfiguration.responsive=window.Amer.setDataTableResponsiveTable();
            window.Amer.dataTableConfiguration.fixedHeader= true;
        }else{
            window.Amer.dataTableConfiguration.responsive= false;
            window.Amer.dataTableConfiguration.scrollX=true;
        }
        if(getPersistentTable){
            window.Amer.dataTableConfiguration.stateSave=true;
            window.Amer.dataTableConfiguration.stateSaveParams=function(settings, data)
            {return window.Amer.PersistentTableSetstateSaveParams(settings, data)};
            if(getPersistentTableDuration){
                window.Amer.dataTableConfiguration.stateLoadParams=function(settings, data){
                    return window.Amer.PersistentTableSetstateLoadParams(settings, data)};
            }
        }
        if(showEntryCount == false){
            window.Amer.dataTableConfiguration.pagingType= "simple_numbers";
        }else{
            window.Amer.dataTableConfiguration.pagingType= 'full_numbers';
        }
        window.Amer.dataTableConfiguration.buttons=window.Amer.exportbtns();
    };
    window.Amer.setDataTableAjax=()=>{
        var data={
            length:$('select[name=AmerTable_length]').val()??parseInt(window.Amer.PageLength('get'))
        };
        return{
            url:window.Amer.prepeareUrl(searchQueryRoute),
            type:'POST',
            data:data,
            async:true,
            contentType :'application/x-www-form-urlencoded',
            crossDomain:true,
            dataType:'json',
            pageLength: parseInt(window.Amer.PageLength('get')),
            beforeSend:function(request){request.setRequestHeader('X-Requested-With','XMLHttpRequest')},
            
        };
    };
    window.Amer.setPersistentTableHref=()=>{
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
    };
    window.Amer.PersistentTableSetstateSaveParams=(settings, data)=>{
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
    window.Amer.PersistentTableSetstateLoadParams=(settings, data)=>{
        var SavedTime = new Date(data.time);
                var CurrentDate = new Date();
                SavedTime.setMinutes(SavedTime.getMinutes() + getPersistentTableDuration);
                if(SavedTime < CurrentDate) {
                    if (localStorage.getItem(StoragelistUrl)) {localStorage.removeItem(StoragelistUrl);}
                    if (localStorage.getItem(StoragelistUrlTime)) {localStorage.removeItem(StoragelistUrlTime);}
                    return false;
                }
    };
    window.Amer.changeDataTableHtmlAfterINIT=()=>{
        $("#AmerTable_filter").appendTo($('#datatable_search_stack' ));
        $("#AmerTable_filter input").removeClass('form-control-sm');
        $("#AmerTable_info").addClass('col-sm');
        $("#datatable_info_stack").append($('#AmerTable_info')).css('display','inline-flex').addClass('animated fadeIn');
        $("#bottom_buttons").insertBefore($('#AmerTable_wrapper .row:last-child' ));
    }
    window.Amer.loadAlerts();
    let StoredPageLength = window.Amer.PageLength('get');
    window.Amer.setPersistentTableHref();
    window.Amer.setDataTableConfiguration();
    $('#datatable_info_stack').addClass('row');
    //after table created
    table=window.Amer.table = $("#AmerTable").DataTable(window.Amer.dataTableConfiguration);
    window.Amer.changeDataTableHtmlAfterINIT();
    window.Amer.updateUrl(location.href);
    window.Amer.resetBtn();
    $.fn.dataTable.ext.errMode = 'none';
    $('#AmerTable').on('error.dt', function(e, settings, techNote, message) {
        //window.Amer.SetErrorMode(e, settings, techNote, message);
    });
    $('#AmerTable').on( 'length', function ( e, settings, len ) {
        table.ajax.reload( null, false ); // user paging is not reset on reload
    } );
    $('#AmerTable').on( 'length.dt', function ( e, settings, len ) {
        settings.ajax.data.length=len;
        window.Amer.PageLength('set',len);
        window.Amer.table.draw('page');
    });
    $('#AmerTable').on( 'draw.dt',   function () {
        
        window.Amer.lineButtonsAsDropdown();
        window.Amer.addFunctionToDataTablesDrawEventQueue('moveExportButtonsToTopRight',window.Amer);
        window.Amer.moveExportButtonsToTopRight();
        if(detailsRow == 1){
            Amer.addFunctionToDataTablesDrawEventQueue('registerDetailsRowButtonAction',window.Amer);
            Amer.registerDetailsRowButtonAction();
            //details-control text-center cursor-pointer m-r-5
        }
        $.each(access,function(k,v){
            if($(`[data-button-type=${v}]`) !== undefined){
                window.Amer.unbindbtns(v,'click');
            }
        });
     }).dataTable();
     $('#AmerTable').on( 'column-visibility.dt',   function (event) {
        window.Amer.responsiveRebuild();
        
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
     /*///////////select ll //////*/
     window.Amer.addOrRemoveAmerCheckedItem=()=>{
        Amer.lastCheckedItem = false;
        document.querySelectorAll('input.Amer_bulk_actions_line_checkbox').forEach(checkbox => checkbox.onclick = e => {
            e.stopPropagation();
            let checked = checkbox.checked;
            let primaryKeyValue = checkbox.dataset.primaryKeyValue;
            Amer.checkedItems ??= [];
            if (checked) {
                Amer.checkedItems.push(primaryKeyValue);
                if (Amer.lastCheckedItem && e.shiftKey) {
                    let getNodeindex = elm => [...elm.parentNode.children].indexOf(elm);
                    let first = document.querySelector(`input.Amer_bulk_actions_line_checkbox[data-primary-key-value="${Amer.lastCheckedItem}"]`).closest('tr');
                    let last = document.querySelector(`input.Amer_bulk_actions_line_checkbox[data-primary-key-value="${primaryKeyValue}"]`).closest('tr');
                    let firstIndex = getNodeindex(first);
                    let lastIndex = getNodeindex(last);
                    while(first !== last) {
                        first = firstIndex < lastIndex ? first.nextElementSibling : first.previousElementSibling;
                        first.querySelector('input.Amer_bulk_actions_line_checkbox:not(:checked)')?.click();
                    }
                }
                Amer.lastCheckedItem = primaryKeyValue;
            }else{
                let index = Amer.checkedItems.indexOf(primaryKeyValue);
                if (index > -1) Amer.checkedItems.splice(index, 1);
            }
            window.Amer.enableOrDisableBulkButtons();
        });
     };
     window.Amer.enableOrDisableBulkButtons=()=>{
        document.querySelectorAll('.bulk-button').forEach(btn => btn.classList.toggle('disabled', !Amer.checkedItems?.length));
     };
     window.Amer.markCheckboxAsCheckedIfPreviouslySelected=()=>{
        let checkedItems = Amer.checkedItems ?? [];
        let pageChanged = localStorage.getItem('page_changed') ?? false;
        let tableUrl = Amer.table.ajax.url();
        let hasFilterApplied = false;
        if (tableUrl.indexOf('?') > -1) {
            if (tableUrl.substring(tableUrl.indexOf('?') + 1).length > 0) {
                hasFilterApplied = true;
            }
        }
        if (! pageChanged && (Amer.table.search().length !== 0 || hasFilterApplied)) {
            Amer.checkedItems = [];
        }
        document.querySelectorAll('input.Amer_bulk_actions_line_checkbox[data-primary-key-value]').forEach(function(elem) {
            let checked = checkedItems.length && checkedItems.indexOf(elem.dataset.primaryKeyValue) > -1;
            elem.checked = checked;
            if (checked && Amer.checkedItems.indexOf(elem.dataset.primaryKeyValue) === -1) {
                Amer.checkedItems.push(elem.dataset.primaryKeyValue);
            }
        });
        localStorage.removeItem('page_changed');
     };
     window.Amer.addBulkActionMainCheckboxesFunctionality=()=>{
        let mainCheckboxes = Array.from(document.querySelectorAll('input.Amer_bulk_actions_general_checkbox'));
        let rowCheckboxes = Array.from(document.querySelectorAll('input.Amer_bulk_actions_line_checkbox'));
        mainCheckboxes.forEach(checkbox => {
            checkbox.checked = document.querySelectorAll('input.Amer_bulk_actions_line_checkbox:not(:checked)').length === 0;
            checkbox.onclick = event => {
                rowCheckboxes.filter(elem => checkbox.checked !== elem.checked).forEach(elem => elem.click());
                mainCheckboxes.forEach(elem => elem.checked = checkbox.checked);
                event.stopPropagation();
            };
        });
        document.querySelectorAll('table td.dtr-control a').forEach(link => link.onclick = e => e.stopPropagation());
     };
     


     Amer.addFunctionToDataTablesDrawEventQueue('addOrRemoveAmerCheckedItem');
    Amer.addFunctionToDataTablesDrawEventQueue('markCheckboxAsCheckedIfPreviouslySelected');
    Amer.addFunctionToDataTablesDrawEventQueue('addBulkActionMainCheckboxesFunctionality');
    Amer.addFunctionToDataTablesDrawEventQueue('enableOrDisableBulkButtons');
})(jQuery);