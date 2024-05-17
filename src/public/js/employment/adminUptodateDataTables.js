localStorage.removeItem('selectedids')
$('textarea[name=uptoids]').val('');
var operations=document.getElementById('operations');
$.ajaxPrefilter(function(options, originalOptions, xhr) {
    var token = $('meta[name="csrf_token"]').attr('content');
    if (token) {
        //return xhr.setRequestHeader('X-XSRF-TOKEN', token);
    }
});
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
function stringify(obj) {
  let cache = [];
  let str = JSON.stringify(obj, function(key, value) {
    if (typeof value === "object" && value !== null) {
      if (cache.indexOf(value) !== -1) {
        // Circular reference found, discard key
        return;
      }
      // Store value in our collection
      cache.push(value);
    }
    return value;
  });
  cache = null; // reset the cache
  return str;
}
function setcolumns(data,e){
    columns= [
        //id
        { data: 'id' },
        //nid
        { data: 'NID' },
        //annonce
        { data: function(data){return data['Annonce_id']['Number']+'/'+data['Annonce_id']['Year'];} },
        //last Entry
        { data: function(data){return data['Stage_id']['LastEntry']['Text'];}},
        { data: function(data){return data['Stage_id']['LastEntry']['Result'];} },
        { data: function (data){if(data['Stage_id']['LastEntry']['Message'] == '' || data['Stage_id']['LastEntry']['Message'] == null){data['Stage_id']['LastEntry']['Message']="-";}return limitText(data['Stage_id']['LastEntry']['Message'],100);} },
        //last convert
        { data: function(data){if(data['Stage_id']['LastStage']== null){data['Stage_id']['LastStage']=[];data['Stage_id']['LastStage']['Text']='-';}return data['Stage_id']['LastStage']['Text'];}},
        {data:function(row, type, set){if(row.Stage_id.LastStage == null || row.Stage_id.LastStage == 'null' || row.Stage_id.LastStage == ''){return '-';}else{return row.Stage_id.LastStage.Result;}}},
        {data:function(row, type, set){if(row.Stage_id.LastStage == null || row.Stage_id.LastStage == 'null' || row.Stage_id.LastStage == ''){return '-';}else{return limitText(row.Stage_id.LastStage.Message,100);}}},
        //Last Stage
        { data: function(data){return data['Stage_id']['Last']['Text'];}},
        { data: function(data){return data['Stage_id']['Last']['Result'];} },
        { data: function (data){if(data['Stage_id']['Last']['Message'] == '' || data['Stage_id']['Last']['Message'] == null){data['Stage_id']['Last']['Message']="-";}return limitText(data['Stage_id']['Last']['Message'],100);} },
        //////////
        { data: function(data){return data['Job_id']['Code']+' :: '+limitText(data['Job_id']['Text'],100);} },
        { data: function(data){return data['Fname']+' '+data['Sname']+' '+data['Tname']+' '+data['Lname']}},
        { data: function(data){return limitText(data['BornGov']+' - '+data['BornCity'],100)} },
        { data: function(data){return limitText(data['LiveGov']+' - '+data['LiveCity']+' - '+data['LiveAddress'],100)}},
        { data: function(data){return data.Sex;}},
        { data: function(data){return data.BirthDate;}},
        { data: function(data){return data['AgeYears']+'-'+data['AgeMonths']+'-'+data['AgeDays']}},
        { data: function(data){return `<td><a href="tel:`+data['ConnectLandline']+`" aria-label="`+data['ConnectLandline']+`"><span class="fa fa-phone"></span></a><a href="tel:`+data['ConnectMobile']+`" aria-label="`+data['ConnectMobile']+`"><span class="fa fa-mobile"></span></a><a href="mailto:`+data['ConnectEmail']+`" aria-label="`+data['ConnectEmail']+`"><span class="fa fa-envelope"></span></a>`} },
        { data: 'Health_id' },
        { data: 'MaritalStatus_id' },
        { data: 'Arm_id' },
        { data: 'Ama_id' },
        { data: 'Tamin' },
        { data: function(data){
            if(data.Khebra[1] == 0){return jstrans['exper_2'];}
            else{
                if(data.Khebra[0] == 0){
                    return jstrans['exper_0']+"("+data.Khebra[1]+")";
                }else{
                    return jstrans['exper_1']+"("+data.Khebra[1]+")";
                }
                
            }
        } },
        { data: function(data){return data.Education_id + "::" + data.EducationYear;} },
        { data: 'DriverDegree' },{ data: 'DriverStart' },{ data: 'DriverEnd' },
        { data: function(data){
            liststages='';
            $.each(data['Stage_id']['stages'],function(k,M){
                liststages+=`<span role="link" data-data='`+JSON.stringify(M)+`'> - `+M['Text']+`</span><br>`
            });
            return liststages;
        }},
        { data: function(data){
            if(data['Seatings'].length === 0){return '-';}
            var Seatings=JSON.stringify(data['Seatings'],null,2).toString().replace(/"/g,"");
            var doewn=`<div class="row" data-for="Seatings" data-id="${data['id']}" data-data="">`;
            $.each(data['Seatings'],function(k,v){
                doewn+=`<div class="col-sm-2">${v['Number']}</div><div class="col-sm-10">${v['Text']}</div>`;
            });
            doewn+='</div>';
            return doewn;
        }},
        { data: function(data){
            var doewn='';
            $.each(data['FileName'],function(k,v){
                if(v['link'] !== null){
                    doewn+=`<a href="`+v['link']+`" target="_blank"><span class="text-success fa fa-download"></span></a>`;
                }else{
                    doewn+=`<i class="text-danger fa fa-exclamation-triangle"></i>`;
                }
            });
            return doewn;
        }},
    ];
    return columns;
}

viewallstages=function(e){
    data=JSON.parse($(e).attr('data-data'));
    var tr=$(e).parent().parent();
    var trchilds=$(tr).children();
    var idtd=$(trchilds)[0];
    var fullnametd=$(trchilds)[13];
    var uid=$(idtd).html();
    var fullname=$(fullnametd).html();
    var html="";
    if(data.Message == null){data.Message='-';}
    html+=`
    <div class="row">
        <div class="col-sm-3">`+jstrans['Employment_Reports']['lastStage']['stage']+`</div><div class="col-sm-9">`+data.Text+`</div>
        <div class="col-sm-3">`+jstrans['Employment_Reports']['lastStage']['stageresult']+`</div><div class="col-sm-9">`+data.Result+`</div>
        <div class="col-sm-3">`+jstrans['Employment_Reports']['lastStage']['stageMessage']+`</div><div class="col-sm-9">`+data.Message+`</div>
        <div class="col-sm-3">`+jstrans['Employment_Reports']['lastStage']['stageDate']+`</div><div class="col-sm-9">`+data.created_at+`</div>
    </div>
    `;
    console.log(jstrans['Employment_Reports']['lastStage']['stage'],uid);
    showerror(uid+": "+fullname,html,'info');
}
function createrow(data){
    createdRow=function(row,data,dataIndex){
        childs=$(row).children();
        $(row).attr('data-full',JSON.stringify(data))
        $(row).attr('data-id',$(childs[0]).html());
    }
    return createdRow;
}
function columndefs(data){
    columnDefs=[
        //withoutspan
        {target:0,'createdCell':function(td,cellData,rowData,roe,col){$(td).attr('data-for','id');if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:1,'createdCell':function(td,cellData,rowData,roe,col){$(td).attr('data-for','nid');if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:2,'createdCell':function(td,cellData,rowData,roe,col){$(td).attr('data-for','annonce');if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:3,'createdCell':function(td,cellData,rowData,roe,col){$(td).attr('data-for','lastEntryStageName');if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:4,'createdCell':function(td,cellData,rowData,roe,col){$(td).attr('data-for','lastvStageStatus');if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:6,'createdCell':function(td,cellData,rowData,roe,col){$(td).attr('data-for','lastConvertStageName');if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:7,'createdCell':function(td,cellData,rowData,roe,col){$(td).attr('data-for','lastConvertStageStatus');if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:9,'createdCell':function(td,cellData,rowData,roe,col){$(td).attr('data-for','lastStageName');if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:10,'createdCell':function(td,cellData,rowData,roe,col){$(td).attr('data-for','lastStageStatus');if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:13,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:16,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:17,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:18,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:18,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:20,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:21,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:22,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:23,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:24,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:25,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:26,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:27,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:28,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:29,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{$(td).attr('data-search',$(td).html());$(td).attr('data-order',$(td).attr('data-search'));}}},
        //one span
        {target:5,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{var spaan=$(td).children();var span=spaan[0];$(td).attr('data-search',$(span).attr('data'));$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:8,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{var spaan=$(td).children();var span=spaan[0];$(td).attr('data-search',$(span).attr('data'));$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:11,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{var spaan=$(td).children();var span=spaan[0];$(td).attr('data-search',$(span).attr('data'));$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:12,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{var spaan=$(td).children();var span=spaan[0];$(td).attr('data-search',$(span).attr('data'));$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:14,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{var spaan=$(td).children();var span=spaan[0];$(td).attr('data-search',$(span).attr('data'));$(td).attr('data-order',$(td).attr('data-search'));}}},
        {target:15,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{var spaan=$(td).children();var span=spaan[0];$(td).attr('data-search',$(span).attr('data'));$(td).attr('data-order',$(td).attr('data-search'));}}},
        //connection
        {target:19,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{
            var links=$(td).children();
            tel=$(links[0]).attr('aria-label')
            $(td).attr('data-search',$(links[0]).attr('aria-label')+'-'+$(links[1]).attr('aria-label')+'-'+$(links[2]).attr('aria-label'));
        $(td).attr('data-order',$(td).attr('data-search'));}}},
        //stages
        {target:30,'createdCell':function(td,cellData,rowData,roe,col){if($(td).html() == '-'){$(td).attr('data-search','');}else{
            var stg=$(td).children();
            var stgs=new Array();
            $.each(stg,function(k,v){
                if($(v).html() !== ''){stgs.push($(v).html());}
            });
            $(td).attr('data-search',stgs.join(' - '));
        $(td).attr('data-order',$(td).attr('data-search'));}}},
    ];
    return columnDefs;
}
if(window.Amer === undefined){
    window.Amer={};
}
window.Amer.dataTableConfiguration={};
window.Amer.functionsToRunOnDataTablesDrawEvent=[],
window.Amer.addFunctionToDataTablesDrawEventQueue= function (functionName){if (this.functionsToRunOnDataTablesDrawEvent.indexOf(functionName) == -1) {this.functionsToRunOnDataTablesDrawEvent.push(functionName);}},
window.Amer.responsiveToggle= function(dt){$(dt.table().header()).find('th').toggleClass('all');dt.responsive.rebuild();dt.responsive.recalc();},
window.Amer.updateUrl=function (url) {
    let urlStart = pathinfo;
    let urlEnd = url.replace(urlStart, '');
    urlEnd = urlEnd.replace('/search', '');
    let newUrl = urlStart + urlEnd;
    let tmpUrl = newUrl.split("?")[0],
    params_arr = [],
    queryString = (newUrl.indexOf("?") !== -1) ? newUrl.split("?")[1] : false;
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
};
window.Amer.executeFunctionByName= function(str, args) {
    var arr = str.split('.');
    var fn = window[ arr[0] ];
    for (var i = 1; i < arr.length; i++)
    {
        fn = fn[ arr[i] ]; 
    }
    fn.apply(window, args);
}
window.Amer.dataTableConfiguration.renderer= function ( api, rowIdx, columns ) {
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
};
window.Amer.dataTableConfiguration.columnDefs=columndefs();
window.Amer.dataTableConfiguration.columns=setcolumns();
window.Amer.dataTableConfiguration.createdRow=createrow();
window.Amer.dataTableConfiguration.bInfo=true;
window.Amer.dataTableConfiguration.select={};
window.Amer.dataTableConfiguration.select.style='multi';
window.Amer.dataTableConfiguration.fixedHeader= true;
window.Amer.dataTableConfiguration.autoWidth= false;
window.Amer.dataTableConfiguration.deferRender= true;
window.Amer.dataTableConfiguration.processing= true;
window.Amer.dataTableConfiguration.serverSide= true;
window.Amer.dataTableConfiguration.searching= true;
window.Amer.dataTableConfiguration.search= {return:true};
window.Amer.dataTableConfiguration.lengthChange= true;
window.Amer.dataTableConfiguration.pagingTag= 'button';
window.Amer.dataTableConfiguration.pagingType= 'full_numbers';  
window.Amer.dataTableConfiguration.pageLength= 15;
window.Amer.dataTableConfiguration.lengthMenu= [[5,10,15,20,50,100,100,-1],[5,10,15,20,50,100,100,"All"]];

window.Amer.dataTableConfiguration.dataSrc= 'data';
window.Amer.dataTableConfiguration.dom=`<'row hidden'<'col-sm-6'i><'col-sm-6 d-print-none'f>> <'row'<'col-sm-12'tr>> <'row mt-2 d-print-none '<'col-sm-12 col-md-4'l><'col-sm-0 col-md-4 text-center'B><'col-sm-12 col-md-4 'p>>`;
window.Amer.dataTableConfiguration.responsive= {
    details: {
        display: $.fn.dataTable.Responsive.display.modal( {
            header: function ( row ) {
                var data = row.data();
                return jstrans['infoabout']+" : "+data['Fname'] + " "+data['Sname'] + " "+data['Tname'] + " "+data['Lname'];
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
};/*
window.Amer.dataTableConfiguration.drawCallback=function( settings ) {
    currentPage=settings.json.current_page;
    var currentURL = window.location.pathname;
    var pageEle = $('.dataTables_paginate').children().find('a');
    $("a.paginate_button").on("click", function(e){
        e.preventDefault();
    });
};*/
window.Amer.dataTableConfiguration.buttons={
            extend: 'collection',
            text: '<i class="la la-download"></i> '+jstrans['export'],
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
                ,{
                    extend: 'colvis',
                    text: '<i class="la la-eye-slash"></i> '+jstrans['column_visibility'],
                    columns: function ( idx, data, node ) {
                        return $(node).attr('data-visible-in-table') == 'false' && $(node).attr('data-can-be-visible-in-table') == 'true';
                    },
                    dropup: true
                },
                {
                    text: 'Select current page - function',
                    extend: 'selectAll',
                    selectorModifier: function () {
                        return {
                            page: 'current'
                        }
                    }
                }
                ,'selectNone'
            ]
        };
        
function insertInToTable(data,tableTemplate,URL,Section) {
    $('.dbTable').html('');
    $('.dbTable').html($(tableTemplate).html());
    window.Amer.idintifi.operations=$('#operations');
    window.Amer.idintifi.operations.hide();
    window.Amer.dataTableConfiguration.ajax= {
        "url": URL,
        "type": "POST",
        dataType: 'json',
        contentType:'application/x-www-form-urlencoded',
        "data":data,
    };
    window.Amer.table = $("#AmerTable").DataTable(window.Amer.dataTableConfiguration);
    window.Amer.addFunctionToDataTablesDrawEventQueue('moveExportButtonsToTopRight');
    afterinstall();
}
moveExportButtonsToTopRight=function () {
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
  function afterinstall(){
    $("#bottom_buttons").insertBefore($('#AmerTable_wrapper .row:last-child' ));
    $.fn.dataTable.ext.errMode = 'none';
    $('#AmerTable').on('error.dt', function(e, settings, techNote, message) {
        var errorStatus=[402];
        var message=jstrans['errors']['ajax_error_text'];
        if(in_array(errorStatus,settings.jqXHR.status)){
            message=settings.jqXHR.responseJSON.message.message;
        }
        new Noty({
            type: "error",
            text: `<strong>${jstrans['errors']['ajax_error_title']}</strong><br>${message}`
        }).show();
        Amer.table.destroy();
        e.target.remove();
    });
    
    $('#AmerTable').on( 'draw.dt',   function () {
        Amer.functionsToRunOnDataTablesDrawEvent.forEach(function(functionName) {
        Amer.executeFunctionByName(functionName);
        });
        if ($('#AmerTable').data('has-line-buttons-as-dropdown')) {
        formatActionColumnAsDropdown();
        }
    }).dataTable();
    $('#AmerTable').on( 'column-visibility.dt',   function (event) {
        Amer.table.responsive.rebuild();
     } ).dataTable();
     Amer.table.on( 'responsive-resize', function ( e, datatable, columns ) {
        if (Amer.table.responsive.hasHidden()) {
          $("#AmerTable").removeClass('has-hidden-columns').addClass('has-hidden-columns');
         } else {
          $("#AmerTable").removeClass('has-hidden-columns');
         }
    } );
    Amer.table.on( 'select', function ( e, dt, type, indexes ) {
        indexes=Amer.table.rows({ selected: true })[0];
        var operations=document.getElementById('operations');
        var dataIDS=new Array(),fullNames=new Array();
        Amer.table[ type ]( indexes ).nodes().to$().addClass( 'custom-selected' );
        if(Amer.table.rows({ selected: true }).count() >0){
            
            
            indexes.forEach(element => {
                var tr=Amer.table[ type ]( element ).nodes().to$()
                dataIDS.push($(tr).attr('data-id'));
                var childs=$(tr).children();
                fullNames.push($(childs[13]).html())
                
            });
            
            uptoids(dataIDS,fullNames,'select')
            $(operations).show();
        }
    } );
    Amer.table.on( 'deselect', function ( e, dt, type, indexes ) {       
        var indexes=Amer.table.rows({ selected: true })[0];
        var operations=document.getElementById('operations');
        var dataIDS=new Array(),fullNames=new Array();
        Amer.table[ type ]( indexes ).nodes().to$().removeClass( 'custom-selected' );
        if(Amer.table.rows({ selected: true }).count() === 0){
            var activeTabs = window.localStorage.getItem('activeTab');
            if(in_array(['#appliedForGrievance_tab','#searchByNAME_tab','#searchByAnnonce_tab','#searchByNID_tab','#searchByUID_tab'],activeTabs)){
                $(operations).hide();
            }else{
                $(operations).hide();
            }
            $('.updatearea').hide();
            uptoids(null,null,'selectNone')
        }else{
            indexes.forEach(element => {
                var tr=Amer.table[ type ]( element ).nodes().to$()
                dataIDS.push($(tr).attr('data-id'));
                var childs=$(tr).children();
                fullNames.push($(childs[13]).html())
            });
            uptoids(dataIDS,fullNames,'select')
        }
        
    } );
  }
  uptoids=function(dataId,fullName,action){
    var activeTabs = window.localStorage.getItem('activeTab');
    var input=[uptoidsTextarea,PrintidsTextArea,SeatingidsTextarea];
    var tag='selectedids';
    if(action == 'selectNone'){
        localStorage.setItem(tag,'');
        $.each(input,function(k,v){
            $(v).val('');
        });
        return;
    }
    var so={};
    $.each(dataId,function(k,v){
        so[v]=fullName[k];
    })
    localStorage.setItem(tag,JSON.stringify(so));
    $.each(input,function(k,v){
        $(v).val(JSON.stringify(dataId));
    })
  }
  formatActionColumnAsDropdown=function() {
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
            actionCell.prepend('<a class="btn btn-sm px-2 py-1 btn-outline-primary dropdown-toggle actions-buttons-column" href="#" data-toggle="dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">'+jstrans['Actions']+'</a>');
        });
    }

}