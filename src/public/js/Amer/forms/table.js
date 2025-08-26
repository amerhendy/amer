(function(){    
    bpFieldInitTableElement=(element)=>{
        var uniqueid=$(element).attr('uniqueid');
        setSorTableData(uniqueid);
        $(element).val(window.Amer.forms[uniqueid].phparray)
        createTable(uniqueid);
        createHeader(uniqueid);
        createTbody(uniqueid);
        prepareTableNumbers(uniqueid);
        setMovesFN(uniqueid,true);
        setEvents(uniqueid);
        $(window.Amer.forms[uniqueid].tableWrapper).find('input').on('change', function() {
            updateTableFieldJson(uniqueid);
        });
        $(window.Amer.forms[uniqueid].tableWrapper).find('select').on('change', function() {
            updateTableFieldJson(uniqueid);
        });
        updateTableFieldJson(uniqueid);
        $('.col-lg-8').removeClass() ;
    };
    setSorTableData=(uniqueid)=>{
        var element=$('[uniqueid='+uniqueid+']');
        var lod={};
        lod['tableWrapper']=`[bp-field-name=${$(element).name()}][data-field-type=table]`;
        lod['max'] = element.attr('data-max');
        lod['min'] = element.attr('data-min');
        lod['maxErrorTitle'] = element.attr('data-maxErrorTitle');
        lod['maxErrorMessage'] = element.attr('data-maxErrorMessage');
        lod['phparray'] = setPhpArray(element.attr('data-phparray'));
        lod['columnsarray'] = JSON.parse(element.attr('data-columnsarray'));
        lod['changeColumn']=Number(element.attr('data-changeColumn'));
        lod['sort'] = Number(element.attr('data-sort'));
        lod['changeKeys'] = Number(element.attr('data-changeKeys'));
        lod['tableName'] = element.name();
        if(lod['sort'] === 1){
            lod['sortable'] = { items: "tr.sortable"};
        }
        window.Amer.forms[uniqueid]=lod;
        
        
    };   
    setPhpArray=(element)=>{
        if(is_array(element)){
            return element;
        }
        if(typeof JSON.parse(element) == 'string'){
            if(IsValidJSONString(JSON.parse(element))){
                return setPhpArray(JSON.parse(element));
            }else{
                return [];
            }
        }else if(typeof JSON.parse(element) == 'object'){
            return JSON.parse(element);
        }
        
        
    };
    createTable=(uniqueid)=>{
        var Name=window.Amer.forms[uniqueid].tableName;
        newunique=generateUUID().split('-')[0];
        window.Amer.forms[uniqueid].tableuniqueid=newunique;
        var table=$('<table calss="tablesorter table table-dark table-bordered"></table>')
                    .attr('table-name',Name)
                    .attr('uniqueid',newunique)
                    table.append($(`<thead  uniqueid="${newunique}" table-name="${Name}"></thead>`));
                    table.append($(`<tbody uniqueid="${newunique}" table-name="${Name}"></tbody>`));
        $('div[table-name='+Name+']').append(table);
    };
    createHeader=(uniqueid)=>{
        var tableuniqueid=window.Amer.forms[uniqueid].tableuniqueid;
        var tr=$('<tr class="row"></tr>');
        th=``;
        $.each(window.Amer.forms[uniqueid].columnsarray,function(k,v){
            var td=$(`<th class="col-sm" scope="col" style="font-weight: 600!important;" data-number="`+k+`"></th>`);
            if(window.Amer.forms[uniqueid].sort === 1){
                var dragHandle=$(`<div class="dragHandle move-column"></div>`);
                td.append(dragHandle);
            }
            if(window.Amer.forms[uniqueid].changeColumn === 1){
                td.append($(`<div class="add-column" onclick="EventaddColumn(this,'${uniqueid}');"  data-number="${k}"></div>`));
                td.append($(`<div class="delete-col" onclick="EventDeleteColumn(this,'${uniqueid}');"  data-number="${k}"></div>`));
            }
            var HeaderInputs=createHeaderInputs(k,v,uniqueid);
            if(isObjext(v)){
                var dataCellName=Object.keys(v)[0];
                if(isObjext(v[dataCellName].label) === false){
                    td.append(v[dataCellName].label);
                }   
            }
            td.append($(HeaderInputs));
            tr.append(td);
        });
        var actionsth=$('<th datatype="actions" style="width: 50px;"></th>');
        var actionlist=createTRListBtns(uniqueid,'header');
        actionsth.html($(actionlist));
        tr.append(actionsth);
        $(`thead[uniqueid=${tableuniqueid}]`).html(tr);
    };
    createHeaderInputs=(k=null,v=null,uniqueid)=>{
        newunique=generateUUID().split('-')[0];
        var input=`<input `;
        input+=`class="form-control"`;
        input+=`uniqueid=${newunique}`;
        input+=` type="text" `;
        if(window.Amer.forms[uniqueid].changeKeys == 0){
            input+=` readonly `;
        }
        if(isObjext(v)){
            var dataCellName=Object.keys(v)[0];
            var inputinfo=JSON.stringify(v[dataCellName])
            
            if('type' in v[dataCellName]){
                var inputType=v[dataCellName]['type'];
            }
            
            
        }else{
            var dataCellName=v;
            var inputinfo=null;
            var inputType='text';
        }
        input+=`data-cell-name="${dataCellName}" `;
        input+=`value="${dataCellName}" `;
        input+=`placeholder="${dataCellName}" `;
        input+=`data-inputinfo="${inputinfo}" `;
        input+=`data-inputType="${inputType}" `;
        input+=`>`;
        return input;
    };
    createTbody=(uniqueid)=>{
        var tableuniqueid=window.Amer.forms[uniqueid].tableuniqueid;
        var phparray=window.Amer.forms[uniqueid].phparray;
        var columnsarray=window.Amer.forms[uniqueid].columnsarray;
        $.each(window.Amer.forms[uniqueid].phparray,function(k,v){
            var tr=$('<tr class="sortable row" rowid="'+k+'"></tr>');
            $.each(createTRInputs(uniqueid,k,v),function(l,m){
                tr.append(m);    
            });
            //tr.append();
                var actionsth=$('<th datatype="actions" style="width: 50px;" rowid='+k+'></th>');
                var actionlist=createTRListBtns(uniqueid,k);
                actionsth.html($(actionlist));
                tr.append(actionsth);    
            $('tbody[uniqueid='+tableuniqueid+']').append(tr);
        });
        $.each($('tbody[uniqueid='+tableuniqueid+']').find('select'),function(k,v){
            bpFieldInitSelect2Element($(v));
            
        });
        
    };
    createTRInputs=(uniqueid,key,v)=>{
        var tableuniqueid=window.Amer.forms[uniqueid].tableuniqueid;
        var tr=new Array();
        $.each(window.Amer.forms[uniqueid].columnsarray,function(k,v){
            newunique=generateUUID().split('-')[0];
            var td=$(`<td class="col-sm" data-number="${k}"></td>`);
            var elementName=Object.keys(v)[0];
            var inputvalue=window.Amer.forms[uniqueid].phparray[key][elementName];
            var inputType=setInputType(v,elementName);
            if(inputType !== 'select'){
                if(inputType == 'float'){
                    if(!v[elementName].hasOwnProperty('step')){v[elementName].step=0.01;}
                }
                var input=createTextInput(inputType,elementName,inputvalue,k);
            }else{
                var input=createSelectInput(v,elementName,uniqueid,key);
            }
            $(input).attr('data-cell-name',`item.${k}`);
            td.append(input);
            tr.push(td);
            $prpp=['max','min','step','list','maxlength','mixlength','multiple','size','pattern','capture','accept','required'];
            $.each($prpp,function(g,h){
                if(v[elementName].hasOwnProperty(h)){$(input).attr(h,v[elementName][h]);}    
            });
        });
        return tr;
    };
    setInputType=(v,elementName)=>{
        var inputType='text';
        if(!isObjext(v)){
            var inputType='text';
        }else{
            
            if(v[elementName].hasOwnProperty('type')){ 
                if(in_array(['float','color','date','datetime-local','email','file','month','number','password','range','search','tel','text','time','url','week'],v[elementName].type)){var inputType=v[elementName].type;}
                else if(in_array(['checkbox','radio','select','selectgroup'],v[elementName].type)){
                    var inputType='select';
                }
            }
        }
        return inputType;
    };
    createTextInput=(inputType,elementName,inputvalue,k)=>{
        var Teuniqueid = generateUUID();
        Teuniqueid =(Teuniqueid.split('-')[0]);
        var inputCl=`form-control form-control-sm`;
        var input=$(`<input>`);
        if(inputType == null){
            inputType='text';
        }else if(inputType == 'float'){
            inputType='number';
        }
        $(input).attr('type',inputType);
        $(input).attr('uniqueid',Teuniqueid);
        $(input).attr('class',inputCl);
        $(input).attr('id',elementName+"-"+k);
        $(input).attr('name',elementName+"-"+k);
        $(input).attr('value',inputvalue);
        return input;
    };
    createSelectInput=(v,elementName,uniqueid,key)=>{
        var Teuniqueid = generateUUID();
        Teuniqueid =(Teuniqueid.split('-')[0]);
        var input=$(`
            <select
            name="${elementName}-${Teuniqueid}" 
            data-field-is-inline="false"
            data-read-only="false"
            data-language="${window.Amer.LangFallback}"
            class="form-control select2_field"
            uniqueid="${Teuniqueid}"
            ></select>`);
            if(in_array(['checkbox'],v[elementName].type)){
                $(input).attr('multiple','multiple');
            }
            if(in_array(['checkbox','radio','select'],v[elementName].type)){
                if('data' in v[elementName]){
                    $.each(v[elementName].data,function(g,h){
                        if(window.Amer.forms[uniqueid].phparray[key][elementName] == g){var sdsd="selected";}else{var sdsd="";}
                        $(input).append(`<option ${sdsd} value="${g}">${h}</option>`)
                    });
                }
            }else if(in_array(['selectgroup'],v[elementName].type)){
                if('data' in v[elementName]){
                    $.each(v[elementName].data,function(g,h){
                        var optgroup=$(`<optgroup label="${h['name']}"></optgroup>`);
                        $.each(h.data,function(l,m){
                            if(window.Amer.forms[uniqueid].phparray[key][elementName] == g){var sdsd="selected";}else{var sdsd="";}
                            $(optgroup).append(`<option ${sdsd} value="${l}">${m.name}</option>`)
                        });
                       $(input).append(optgroup);
                    });
                }
                
            }
            return input;
    }
    prepareTableNumbers=(uniqueid)=>{
        prepareHeaderNumber(uniqueid)
        prepareTbodyNumber(uniqueid)
    };
    prepareHeaderNumber=(uniqueid)=>{
        var tableuniqueid=window.Amer.forms[uniqueid].tableuniqueid;
        var table=$('table[uniqueid='+tableuniqueid+']');
        th=$(table).find('th[data-number]');
        var thlength=$(th).length;
        tharray=[ ...Array(thlength).keys() ].map( i => i+0)
        $.each(tharray,function(n){
            var vol=$(th)[n];
            input = $(vol).find('input')
            $(input).attr('data-cell-name','item.'+n)
            $.each($(vol).find('[data-number]'),function(l,m){
                $(m).attr('data-number',n);
            })
            $.each($(vol).find('button'),function(l,m){
                $(m).attr('id','col-'+n);
                $(m).removeClass('show');
            })
            $.each($(vol).find('ul'),function(l,m){
                $(m).attr('aria-labelledby','col-'+n);
                $(m).attr('style','');
                $(m).removeClass('show');
            })
            $(vol).attr('data-number',n);
        });
    }
    prepareTbodyNumber=(uniqueid)=>{
        var tableuniqueid=window.Amer.forms[uniqueid].tableuniqueid;
        var table=$('table[uniqueid='+tableuniqueid+']');
        tr=$(table).find('tbody').find('tr');
        var trlength=$(tr).length;
        $.each(tr,function($i,vol){
            $(vol).attr('rowid',$i);
            $(vol).find('th[datatype=actions]').attr('rowid',$i);
            $(vol).find('th[datatype=actions]').find('div[data-number]').attr('data-number',$i);
           //set td and inputs
           $.each($(vol).find('td[data-number]'),function(l,m){
            $(m).attr('data-number',l)
            $.each($(m).find('[data-cell-name]'),function(a,b){
                $(b).attr('data-cell-name','item.'+l);
            })
        }); 
        });
    }    
    createTRListBtns=(uniqueid,row)=>{
        var addRowOnclick=`EventAddRow(this,'${uniqueid}');`;
        var DeleteRowOnclick=`EventDeleteRow(this,'${uniqueid}');`
        var rowClass="";
        if(row === 'header'){
            var rowClass="-header";
            var addRowOnclick=`EventAddRowFromHeader('${uniqueid}');`;
        }
        var tableuniqueid=window.Amer.forms[uniqueid].tableuniqueid;
        var phparray=window.Amer.forms[uniqueid].phparray;
            rowid=row;
            if(rowid == undefined){rowid='header'};
            var buttons=$(`<div class="row"></div>`);
            if(window.Amer.forms[uniqueid].sort === 1){
                if(row !== 'header'){
                    buttons.append($(`<div class="dragHandle move-row"></div>`));
                }
            };
            buttons.append($(`<div class="add-row${rowClass}" data-number="${rowid}" onclick="${addRowOnclick}"></div>`));
            if(row !== 'header'){
            buttons.append($(`<div class="delete-row${rowClass}" data-number="${rowid}" onclick="${DeleteRowOnclick}"></div>`));
            }
            return buttons;
    };
    //https://localhost/loginsystem_Copy/Drivers/office_drivers/9d34deee-89d1-403d-9f69-aa9a8032e3aa/edit
    //////////////////////////////
    setMoveEventJson=(uniqueid)=>{
        tableName=window.Amer.forms[uniqueid].tableName;
        $("table[table-name="+tableName+"]").trigger("update");
        prepareTableNumbers(uniqueid)
        
        
        setMovesFN(uniqueid,false);
        setMovesFN(uniqueid,true);
        setEvents(uniqueid,false);
        setEvents(uniqueid);
        updateTableFieldJson(uniqueid);
    };
    setMovesFN=(uniqueid,st)=>{
        var tableName=window.Amer.forms[uniqueid].tableName;
        if(window.Amer.forms[uniqueid].sort === 1){
            if(st === true){
                var sortabled=window.Amer.forms[uniqueid].sortable;
                var dragtabled={
                    dragHandle: ".move-column",
                    maxMovingRows:1,
                    persistState: function(table) {
                        prepareTableNumbers(uniqueid)
                }};
                var tablesorterd={theme : 'blue',sortList: [[2,1],[0,0]]};
            }else{
                var sortabled='destroy';
                var dragtabled='destroy';
                var tablesorterd='destroy'
            }
            
            $("table[table-name="+window.Amer.forms[uniqueid].tableName+"]")
            .sortable(sortabled)
            .dragtable(dragtabled)
            .tablesorter(tablesorterd);
        }
    };
    setEvents=(uniqueid,st=true)=>{
        $(window.Amer.forms[uniqueid].tableWrapper).find('.sortableOptions').sortable(setSortableOptionsEvents(uniqueid));
        $(window.Amer.forms[uniqueid].tableWrapper).find('.thead').sortable(setTheadEvents(uniqueid));
    };
    setSortableOptionsEvents=(uniqueid)=>{
        return {
            handle: '.sort-handle',
            axis: 'y',
            helper: function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            },
            update: function( event, ui ) {
                updateTableFieldJson(uniqueid);
            }
        }
    };
    setTheadEvents=(uniqueid)=>{
        return {
            handle: '.sort-handle',
            axis: 'x',
            helper: function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            },
            update: function( event, ui ) {
                updateTableFieldJson(uniqueid);
            }
        }
    };
    EventaddColumn=(e,uniqueid)=>{
        tar=e;
        var number=$(tar).attr('data-number')
        var thead=$(tar).parent().parent().parent().parent().parent();
        var tableName=thead.attr('table-name')
        addcol(number,tableName);
        prepareTableNumbers(uniqueid)
        updateTableFieldJson(uniqueid);
        setMovesFN(uniqueid,false);
        setMovesFN(uniqueid,true);
    };
    EventDeleteColumn=(e,uniqueid)=>{
        tar=e;
        var number=$(tar).attr('data-number')
        var thead=$(tar).parent().parent().parent().parent().parent();
        var tableName=thead.attr('table-name')
        removecol(number,tableName);
        prepareTableNumbers(uniqueid)
        updateTableFieldJson(uniqueid);
        setMovesFN(uniqueid,false);
        setMovesFN(uniqueid,true);
    };
    EventAddRow=(e,uniqueid)=>{
        tar=e;
        var number=$(tar).attr('data-number')
        var tbody=$(tar).parent().parent().parent().parent().parent();
        var tableName=tbody.attr('table-name')
        addrow(number,tableName,uniqueid);
        setMoveEventJson(uniqueid);
    };
    EventAddRowFromHeader=(uniqueid)=>{
        tableName=window.Amer.forms[uniqueid].tableName;
        var trl=$('table[table-name='+tableName+']').find('tbody').find('tr[rowid]');
        if(window.Amer.forms[uniqueid].max !== '-1'){
            if(trl.length >= window.Amer.forms[uniqueid].max){
                showerror('',window.Amer.forms[uniqueid].maxErrorMessage);
                console.log(window.Amer.forms[uniqueid].maxErrorMessage);
                return;
            }
        }
        if(window.Amer.forms[uniqueid].phparray.length === 0){window.Amer.forms[uniqueid].phparray=[{}];}
        createTbody(uniqueid);
        setMoveEventJson(uniqueid);
    };
    EventDeleteRow=(e,uniqueid)=>{
        tar=e;
        var number=$(tar).attr('data-number')
        var tbody=$(tar).parent().parent().parent().parent();
        var tableName=tbody.attr('table-name')
        var tr=$('table[table-name='+tableName+']').find('tbody').find('tr[rowid='+number+']');
        $(tr).remove();
        prepareTableNumbers(uniqueid)
        updateTableFieldJson(uniqueid)
    };
    addcol=(number,tableName)=>{
        var th=$('table[table-name='+tableName+']').find('thead').find('tr').find('th[data-number='+number+']');
        var dropdownmenu=$(th).find('.dropdown-menu');
        $(dropdownmenu).dropdown('toggle');
        var cloneth=$('th[data-number='+number+']').clone();
        $(cloneth).insertAfter($(th));
        var td=$('table[table-name='+tableName+']').find('tbody').find('tr').find('td[data-number='+number+']');
        $.each(td,function(a,b){
            var tdclone=$(b).clone();
            $(tdclone).find('input').val('');
            $(tdclone).insertAfter($(b))
        })
    };
    removecol=(number,tableName)=>{
        var th=$('table[table-name='+tableName+']').find('thead').find('tr').find('th[data-number='+number+']');
        var dropdownmenu=$(th).find('.dropdown-menu');
        $(dropdownmenu).dropdown('toggle');
        $(th).remove();
        var td=$('table[table-name='+tableName+']').find('tbody').find('tr').find('td[data-number='+number+']');
        $.each(td,function(a,b){
            $(b).remove()
        })
    };
    addrow=(number,tableName,uniqueid)=>{
        var tr=$('table[table-name='+tableName+']').find('tbody').find('tr[rowid='+number+']');
        var trl=$('table[table-name='+tableName+']').find('tbody').find('tr[rowid]');
        if(trl.length >= window.Amer.forms[uniqueid].max){
            showerror('',window.Amer.forms[uniqueid].maxErrorMessage);
            console.log(window.Amer.forms[uniqueid].maxErrorMessage);
            return;
        }
        if(tr.length === 0){
            addrowfromcolumnsarray(uniqueid);
        }
        var dropdownmenu=$(tr).find('.dropdown-menu');
        $(dropdownmenu).dropdown('toggle');
        var clonetr=$(tr).clone();
        $(clonetr).insertAfter($(tr));
        var input=$(clonetr).find('input');
        $.each(input,function(a,b){
            $(b).val('');
        })

    }
    addrowfromcolumnsarray=(uniqueid)=>{
        var tableuniqueid=window.Amer.forms[uniqueid].tableuniqueid;
        var tbody=$('tbody[uniqueid='+tableuniqueid+']');
        tr=$('<tr class="row"></tr>');
        $.each(window.Amer.forms[uniqueid].columnsarray,function(k,v){
            newunique=generateUUID().split('-')[0];
            var th=`<th class="col-sm" style="font-weight: 600!important;" data-number="`+k+`">`;
            th+=`<div class="dragHandle move-column"></div>`;
            if(window.Amer.forms[uniqueid].changeColumn === 1){
                th+=`<div class="add-column" onclick="EventaddColumn(this,'${uniqueid}');" data-number="${k}"></div>`;
                th+=`<div class="deleae-col" data-number="${k}"></div>`;
            }
            th+=`<input 
                    class="form-control form-control-sm" 
                    type="text" 
                    unique="${newunique}" 
                    id="${newunique}" 
                    data-cell-name="item.${k}" readonly`;
                if(!isObjext(v)){
                            th+=`
                            value="${v}" 
                            placeholder="${v}" 
                            `;
                        }else{
                            var N=Object.keys(v)[0];
                            th+=`
                            value="${N}" 
                            placeholder="${N}" 
                            data-inputinfo='${JSON.stringify(v[N])}'
                            `;
                            /*
                            if(v[N].hasOwnProperty('type')){
                                if(in_array(['color','date','datetime-local','email','file','month','number','password','range','search','tel','text','time','url','week'],v[N].type)){
                                    th+=`
                                        <input 
                                                class="form-control form-control-sm" 
                                                type="${v[N].type}" 
                                                unique="${newunique}" 
                                                id="${newunique}" 
                                                data-cell-name="item.${k}" 
                                                placeholder="${N}"
                                    `;
                                    if(v[N].hasOwnProperty('max')){th+=` max="${v[N].max}"`;}
                                    if(v[N].hasOwnProperty('min')){th+=` min="${v[N].min}"`;}
                                    if(v[N].hasOwnProperty('step')){th+=` step="${v[N].step}"`;}
                                    if(v[N].hasOwnProperty('list')){th+=` list="${v[N].list}"`;}
                                    if(v[N].hasOwnProperty('maxlength')){th+=` maxlength="${v[N].maxlength}"`;}
                                    if(v[N].hasOwnProperty('minlength')){th+=` minlength="${v[N].minlength}"`;}
                                    if(v[N].hasOwnProperty('multiple')){th+=` multiple="${v[N].multiple}"`;}
                                    if(v[N].hasOwnProperty('pattern')){th+=` pattern="${v[N].pattern}"`;}
                                    if(v[N].hasOwnProperty('size')){th+=` size="${v[N].size}"`;}
                                    if(v[N].hasOwnProperty('capture')){th+=` capture="${v[N].capture}"`;}
                                    if(v[N].hasOwnProperty('accept')){th+=` accept="${v[N].accept}"`;}
                                    if(v[N].hasOwnProperty('required')){th+=` required`;}
                                    th+=` readonly>`;
                        
                                }
                            }*/
                        }
                        th+='>';
                        
            th+=`</th>`;
            tr.append(th);
            tbody.append(tr)
        });
        createTRListBtns(uniqueid);
    };
    updateTableFieldJson=(uniqueid)=>{
        var myTable = { myTable: [] };
        $tableWrapper=$(window.Amer.forms[uniqueid].tableWrapper);
        var $hiddenField = $tableWrapper.find('input.array-json');
        var $th = $($tableWrapper).find($('table th[data-number]'));
        $($tableWrapper).find($('table tbody tr')).each(function(i, tr){
            var obj={}, $tds = $(tr).find('td[data-number]');
            $.each($tds,function(k,v){
                var input=$(v).find('[data-cell-name]');
                
                var itemnumber=input.attr('data-cell-name').split('.')[1];
                var indexName=$(th).find('[data-cell-name="item.'+itemnumber+'"]').val();
                var inputval=$(v).find('[data-cell-name="item.'+itemnumber+'"]').val();
                obj[indexName]=inputval;
            });
            myTable.myTable.push(obj);
        });
        $json=JSON.stringify(myTable.myTable, null, 2);
        $hiddenField.val($json);
    }
    bpFieldInitSelect2Element=function(element) {
        if (!element.hasClass("select2-hidden-accessible"))
                {
                    let $isFieldInline = element.data('field-is-inline');
                    let $disabled=element.data('read-only');
                    var uniqueid=$(element).attr('uniqueid');
                    window.Amer.forms[uniqueid]={};
                    registerSelect2WantedData(uniqueid);
                    select2f=setSelect2Info($(element).attr('uniqueid'));
                    element.select2(select2f);
                }
        }
})(jQuery);
