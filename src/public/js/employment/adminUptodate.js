(function(){
$.ajaxSetup({ cache: true });
//$(document).ajaxStart(function() { Pace.restart(); });
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
window.Amer={};
window.Amer.a={a:'a'};
loadDefaultTabs=function(){var activeTabs = window.localStorage.getItem('activeTab');
if (activeTabs) {
    var activeTabs = (window.localStorage.getItem('activeTab') ? window.localStorage.getItem('activeTab').split(',') : []);
    $.each(activeTabs, function (index, element) {
        var triggerEl = document.querySelector('[data-bs-toggle="tab"][data-bs-target="' + element + '"]')
        if(triggerEl !== null){bootstrap.Tab.getOrCreateInstance(triggerEl).show();}
    });
}else{
    var triggerEl = document.querySelector('button[data-bs-toggle="tab"]')
        if(triggerEl !== null){bootstrap.Tab.getOrCreateInstance(triggerEl).show();}
}}
Noty.overrideDefaults({
    layout   : 'center',
    theme    : 'mint',
    timeout: 3000,
    progressBar: true,
    closeWith:['click'],
    type :'alert',
    animation:{
        open:'noty_effects_open',
        close:'noty_effects_close',
    }   

});
if(window.Amer === undefined){
    window.Amer={};
}
window.Amer.idintifi={};
var Sections={annonce:'AnnonceAnnonce',Grievance:'GrievanceAnnonce',nid:'NIDINPUT',uid:'UIDINPUT',name:'NameINPUT',Seating:'SeatingAnnonce',Filter:'Filter'};
var SearchByAnnonceElements=['AnnonceAnnonce','AnnonceStage','AnnonceJob','AnnonceStatus','AnnonceStart','AnnonceEnd','Annoncelength','byAnnonceShow'];
var GrievanceElements=['GrievanceAnnonce','GrievanceJob','GrievanceType','GrievanceResult','Grievanceshow','GrievanceStart','GrievanceEnd','GrievanceLength'];
var Seatingelements=['SeatingAnnonce','SeatingJob','SeatingStages','SeatingStart','SeatingEnd','Seatingshow','SeatingLength','forSeatingTablesSelect'];
var FilterElements=['Filters','FiltersRes','FiltersAnnonce','FiltersJob','FiltersStages','FiltersStart','FiltersEnd','Filtersshow','FiltersLength'];
var seatings_Stages=[14,13,7];
var tabsInputs=['NIDINPUT','nidshow','UIDINPUT','uidshow','NameINPUT','nameShow'];
var TemplatesElements=['dbTable'];
var operationsbtns=['uptodatecols','printcols','downloadcols','Seatingcols'];
var idsTextarea=['uptoidsTextarea','PrintidsTextArea','SeatingidsTextarea','FilteridsTextarea']
var unknownelements=['operations','updatearea','new_stage','new_res','new_Degrees_div','SeatingForm','printform','downloadform','selectMessageTemplate'];
$.each(SearchByAnnonceElements,function(k,v){eval('var '+v+ '='+ "document.getElementById('"+v+"');");});
$.each(GrievanceElements,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});
$.each(Seatingelements,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});
$.each(FilterElements,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});

$.each(tabsInputs,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});
$.each(TemplatesElements,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});
$.each(operationsbtns,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});
$.each(idsTextarea,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});
$.each(unknownelements,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});
addIdentifytoWindowAmer=function(list){
    $.each(list,function(k,v){
        $mainel=k;
        $.each(v,function(a,b){
            window.Amer.idintifi[b]=document.getElementById(b);
        });
        //window.Amer.idintifi[k]=v;
    });
}
addIdentifytoWindowAmer({Sections,SearchByAnnonceElements,GrievanceElements,Seatingelements,tabsInputs,TemplatesElements,operationsbtns,idsTextarea,unknownelements,FilterElements});
removeallSelect2ElementsOptions=function(arr){
    $.each(arr,function(k,v){
        removeAllOptions(document.getElementById(v));        
    });
}
removeallSelect2ElementsOptions(SearchByAnnonceElements);
removeallSelect2ElementsOptions(GrievanceElements);
NIDINPUT.value = '';
UIDINPUT.value = '';
NameINPUT.value = '';
document.getElementById("editor1").value = '';
loadDefaultTabs();
$('button[data-bs-toggle="tab"]').on('click', function (e) {
    var theTabId = $(this).attr('data-bs-target');
    window.localStorage.setItem('activeTab', theTabId);
    });
    saveLocalData=function(tag,data){
        window.localStorage.setItem(tag, data);
    }
    getSavedLocalData=function(tag){
        var ActiveTag=window.localStorage.getItem(tag);
        if(ActiveTag == 'undefined'){return false;}
        if(ActiveTag){
            var ActiveTag = (window.localStorage.getItem(tag) ? window.localStorage.getItem(tag).split(',') : false);
            return ActiveTag;
        }else{
            return false;
        }
    }
    faileajax=function(e,FN){
        par=$(e).parent();
        ref=$('<i class="fa fa-refresh text-success" aria-hidden="true" data-bs-refresh="'+$(e).attr('id')+'"></i>')
        $(par).append($(ref))
        $(ref).on('click',function(){
            window[FN](e)
        });
    };
    set_GrievanceType=function(e){
        grtypes={
            Grievance_Practical:jstrans['Employment_Grievance']['PracticalGrievance'],
            Grievance_apply:jstrans['Employment_Grievance']['AppliedGrievance'],
            WritingGrievance:jstrans['Employment_Grievance']['WritingGrievance']};
        $.each(grtypes,function(k,v){
            $('<option value="' + k + '"/>').html(v).appendTo(e);
        });
    }
    setGrievanceResult=function(e){
        grres={
            0:jstrans['Employment_Reports']['AllGrievance'],
            1:jstrans['Employment_Reports']['AcceptGrievance'],
            2:jstrans['Employment_Reports']['NAcceptGrievance']};
        $.each(grres,function(k,v){
            $('<option value="' + k + '"/>').html(v).appendTo(e);
        });
    }
    SetStatusOptions=function() {
        var tag='Employment_Status';
        //removeAllOptions(document.getElementById('new_res'));
        if(getSavedLocalData(tag)){
            return SetOptions(tag,JSON.parse(getSavedLocalData(tag)));
        }
        opt = 'body';
        var URL = api + "status";
        jQuery.ajax({
            url: URL,
            dataType: 'json',
            type: 'post',
            cache: true,
            crossDomain: true,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$(AnnonceStatus).attr('id')+']').remove();
            },
            complete: function() {
                remove_loader_div();
            },
            success: function(data) {
                saveLocalData(tag,JSON.stringify(data.data));
                SetOptions(tag,data.data);
            },
            error: function(e, xhr, opt) {
                remove_loader_div();faileajax(AnnonceStatus,'SetStatusOptions');
                console.log("error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            }
        });
    };
    setStages=function(e) {
        var tag='Employment_Stages';
            $local=getSavedLocalData(tag);
            if(getSavedLocalData(tag) !== false){return SetOptions(tag,JSON.parse(getSavedLocalData(tag)));}
        var link = api + 'stages'
        jQuery.ajax({
            url: link,
            dataType: 'json',
            type: 'post',
            cache: true,
            crossDomain: true,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$(AnnonceStage).attr('id')+']').remove();
            },
            complete: function() {
                remove_loader_div();
            },
            success: function(data) {
                saveLocalData(tag,JSON.stringify(data.data));
                SetOptions(tag,data.data)
                view_noty("success", "تم تحميل المراحل");
            },
            error: function(e, xhr, opt) {
                remove_loader_div();faileajax(AnnonceStage,'setStages');
                console.log("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            }
        });
    };
    setAnnNames=function(){
        var tag='Employment_StartAnnonces'
        if(getSavedLocalData(tag)){
            return SetOptions(tag,JSON.parse(getSavedLocalData(tag)));
        }
        var link = api + 'Annonces'
        jQuery.ajax({
            url: link,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$(AnnonceAnnonce).attr('id')+']').remove();
            },
            complete: function() {
                remove_loader_div();
            },
            dataType: 'json',
            type: 'post',
            cache: true,
            crossDomain: true,
            success: function(data) {
                saveLocalData(tag,JSON.stringify(data.data));
                SetOptions(tag,data.data)
            },
            error: function(e, xhr, opt) {
                remove_loader_div();faileajax(AnnonceAnnonce,'setAnnNames');
                console.log("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            }
        });
    };
    
    SetFilters=function(e){
        var tag='EmploymentFilters';
        var elementId=$(e).id();
        console.log($(Filters).val());
        if(getSavedLocalData(tag)){
            return SetOptions(tag,JSON.parse(getSavedLocalData(tag)));
        }
        var link = api + 'Filters';
        var FilterVal=$(Filters).val();
        data={FilterVal};
        jQuery.ajax({
            url: link,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$('#'+elementId).attr('id')+']').remove();
            },
            complete: function() {
                remove_loader_div();
            },
            dataType: 'json',
            type: 'post',
            data:JSON.stringify(data),
            cache: true,
            crossDomain: true,
            success: function(data) {
                saveLocalData(tag,JSON.stringify(data.data));
                SetOptions(tag,data.data)
            },
            error: function(e, xhr, opt) {
                remove_loader_div();faileajax($('#'+elementId),elementId);
                console.log("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            },
            statusCode: {
                402: function(e) {
                    if(isObjext(e.responseJSON)){
                        var js=e.responseJSON;
                        if(objkey_exists(js,'message')){
                            if(isObjext(e.responseJSON.message)){
                                js=e.responseJSON.message;
                                if(objkey_exists(js,'message')){
                                    showerror('',js.message);
                                    console.log(js);
                                }
                            }
                        }
                        
                    }
                    
                  //alert( "page not found" );
                }
              }
        });
    }
    set_job=function(e){
        var elementId=$(e).id();
        if(elementId == 'AnnonceJob'){
            var AnnonceVal=$(AnnonceAnnonce).val();
        }
        if(elementId == 'GrievanceJob'){
            var AnnonceVal=$(GrievanceAnnonce).val();
        }
        if(elementId == 'SeatingJob'){
            var AnnonceVal=$(SeatingAnnonce).val();
        }
        if(AnnonceVal == '')return;
        var tag='Employment_Jobs_'+elementId+'_'+AnnonceVal;
        if(getSavedLocalData(tag)){
            return SetOptions(tag,JSON.parse(getSavedLocalData(tag)));
        }
        var link = api + 'AnnonceJobs'
        data={AnnonceVal};
        jQuery.ajax({
            url: link,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$('#'+elementId).attr('id')+']').remove();
            },
            complete: function() {
                remove_loader_div();
            },
            dataType: 'json',
            type: 'post',
            data:JSON.stringify(data),
            cache: true,
            crossDomain: true,
            success: function(data) {
                saveLocalData(tag,JSON.stringify(data.data));
                SetOptions(tag,data.data)
            },
            error: function(e, xhr, opt) {
                remove_loader_div();faileajax($('#'+elementId),'set_job');
                console.log("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            }
        });
        
    };
    SetOptions=function(tag,data){
        if(tag == 'Employment_StartAnnonces')
        {
            data.forEach(function(item, k) {
                $('<option data-id="' + item['id'] + '" value="' + item['Slug'] + '"/>').html(item['Number'] + ' / ' + item['Year']).appendTo(AnnonceAnnonce);
                $('<option data-id="' + item['id'] + '" value="' + item['Slug'] + '"/>').html(item['Number'] + ' / ' + item['Year']).appendTo(GrievanceAnnonce);
                $('<option data-id="' + item['id'] + '" value="' + item['Slug'] + '"/>').html(item['Number'] + ' / ' + item['Year']).appendTo(SeatingAnnonce);
            });
        }
        if(tag == 'Employment_Stages')
        {
            data.forEach(function(item, k) {
                $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(AnnonceStage);
                $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(new_stage);
                if(seatings_Stages.includes(item['id'])){
                    $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(SeatingStages);
                    $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo($('#SeatingPrintStage'));
                }
                
            });
        }
        
        if(tag == 'Employment_Status')
        {
            data.forEach(function(item, k) {
                $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(AnnonceStatus);
                $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(new_res);
            });
        }
        if(tag.includes('Employment_Jobs')){
            var $split=tag.split('Employment_Jobs');
            var $split=$split[1].split('_');
            var elementId=$split[1];
            var element=$('#'+elementId);
            data.forEach(function(item, k) {
                $('<option value="' + item['id'] + '"/>').html(item['Code'] + '::' + item['text']).appendTo(element);
            });
        }
    };
    setSelect2=function(){
        $.each($('[data-set-select2]'),function(key,value){
            var functionName=$(value).data('set-select2');
            if(functionName == null){
                $(value).select2();
                return;
            }
            if(value.localName === 'div'){
                return window[functionName](value);
            }
            if(value.options.length == 0){
                $('<option value="">').html('').appendTo($(value));
            }
            $(value).select2();
            if (typeof window[functionName] === "function") {
                window[functionName](value);
            }
        });
    };
    setcount=function(section){
        if(section == Sections.annonce){
            var input=AnnonceAnnonce;
            var len=Annoncelength;
            var data={
                        'Annonce_id':$(AnnonceAnnonce).val(),
                        'Job_id':$(AnnonceJob).val(),
                        'Stage_id':$(AnnonceStage).val(),
                        'Status_id':$(AnnonceStatus).val(),
                        section
                    };
        }
        if(section == Sections.Grievance){
            var input=GrievanceAnnonce;
            var len=GrievanceLength;
            var data={
                'Annonce_id':$(GrievanceAnnonce).val(),
                'Job_id':$(GrievanceJob).val(),
                'GrievanceType':$(GrievanceType).val(),
                'GrievanceResult':$(GrievanceResult).val(),
                section
            };
        }
        if(section == Sections.Seating){
            var input=SeatingAnnonce;
            var len=SeatingLength;
            
            var data={
                'Annonce_id':$(SeatingAnnonce).val(),
                'Job_id':$(SeatingJob).val(),
                'Stage_id':$(SeatingStages).val(),
                section
            };
        }
        link=api+'employmentReports/Counts'
        jQuery.ajax({
            url: link,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$(input).attr('id')+']').remove();
            },
            complete: function() {
                remove_loader_div();
            },
            dataType: 'json',
            type: 'post',
            data:JSON.stringify(data),
            cache: true,
            crossDomain: true,
            success: function(data) {
                $(len).html(data.recordsTotal);
                setStart(section,data.data);
            },
            error: function(e, xhr, opt) {
                if(e.readyState == 4 && e.status == 402){
                    new Noty({
                        type: "error",
                        text: `<strong>${jstrans['errors']['ajax_error_title']}</strong><br>${e.responseJSON.message}`
                    }).show();
                }
                remove_loader_div();faileajax(input,'setcount');
            },
            statusCode: {
                402: function(e) {
                    if(isObjext(e.responseJSON)){
                        var js=e.responseJSON;
                        if(objkey_exists(js,'message')){
                            if(isObjext(e.responseJSON.message)){
                                js=e.responseJSON.message;
                                if(objkey_exists(js,'message')){
                                    showerror('',js.message);
                                    console.log(js);
                                }
                            }
                        }
                        
                    }
                    
                  //alert( "page not found" );
                }
              }
        });
    }
    setStart=function(section,data){
        if(section == Sections.annonce){
            var startInput=AnnonceStart;
            var EndInPut=AnnonceEnd;
            var byAnnonceShown=byAnnonceShow;
        }
        if(section == Sections.Grievance){
            var startInput=GrievanceStart;
            var EndInPut=GrievanceEnd;
            var byAnnonceShown=Grievanceshow;
        }
        if(section == Sections.Seating){
            var startInput=SeatingStart;
            var EndInPut=SeatingEnd;
            var byAnnonceShown=Seatingshow;
        }
        removeAllOptions(document.getElementById($(startInput).id()));
        removeAllOptions(document.getElementById($(EndInPut).id()));
        if(!Array.isArray(data))
        {
            console.log('Start Not Array');
            removeAllOptions(document.getElementById($(startInput).id()));
            removeAllOptions(document.getElementById($(EndInPut).id()));
            return;
        }
        if(data.length== 0)
        {
            console.log('Start length = 0');
            removeAllOptions(document.getElementById($(startInput).id()));
            removeAllOptions(document.getElementById($(EndInPut).id()));
            return;
        }
       //data= data.sort((a, b) => a - b);
       data.sort((a, b) => a - b).forEach(function(item, k) {
        $('<option value="' + item + '"/>').html(item).appendTo(startInput);
        $('<option value="' + item + '"/>').html(item).appendTo(EndInPut);
        });
        $(byAnnonceShown).show();
    }
    setEnd=function(section){
        if(section == Sections.annonce){
            startInput=AnnonceStart;
            EndInPut=AnnonceEnd;
        }
        if(section == Sections.Grievance){
            var startInput=GrievanceStart;
            var EndInPut=GrievanceEnd;
        }
        var options=new Array();
        $.each($(startInput).prop("options"), function(i, opt) {
            options.push(parseInt(opt.value));
        })
        options=options.slice($(options).index(parseInt($(startInput).val())));
        options.sort((a, b) => a - b).forEach(function(item, k) {
            $('<option value="' + item + '"/>').html(item).appendTo(EndInPut);
        });
    }
    setMessages=function(){
        var tag='message_template'
        if(getSavedLocalData(tag)){
            return ;
        }
        var link = api + 'employmentReports/message_template'
        var input=MessageTemplateInput;
        jQuery.ajax({
            url: link,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$(input).attr('id')+']').remove();
            },
            complete: function() {
                remove_loader_div();
            },
            dataType: 'json',
            type: 'post',
            cache: true,
            crossDomain: true,
            success: function(data) {
                saveLocalData(tag,JSON.stringify(data));
            },
            error: function(e, xhr, opt) {
                remove_loader_div();faileajax(input,'setAnnNames');
                console.log("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            }
        });
    }
    perpareTableAnnonce=function(Section){
        startInput=AnnonceStart;
        EndInPut=AnnonceEnd;
        var tableTemplate=dbTable;
        var options=new Array();
        $.each($(startInput).prop("options"), function(i, opt) {
            options.push(parseInt(opt.value));
        })
        var StartIndex=$(options).index(parseInt($(startInput).val()));
        var EndIndex=$(options).index(parseInt($(EndInPut).val()))+1;
        var data={id:options.slice(StartIndex,EndIndex),"totalEntryCount": true,Section};
        return {data,tableTemplate};
    }
    perpareTablenid=function(Section){
        var elementExists = document.getElementById(Section);
            if(elementExists == null){return showerror('404',jstrans['Employment_Reports']['errors']['nidInputNotFound']);}
            var nidvalue=elementExists.value;
            nidvalue=nidvalue.trim();
            if(nidvalue == ''){return showerror('404',jstrans['Employment_Reports']['errors']['NidInputEmpty']);}
            if(nidvalue.length < 6){return showerror('404',jstrans['Employment_Reports']['errors']['indInputLessThan']);}
            var data={nid:nidvalue,"totalEntryCount": true,Section};
            var tableTemplate=dbTable;
            return {data,tableTemplate};
    }
    perpareTableUid=function(Section){
        var elementExists = document.getElementById(Section);
            if(elementExists == null){return showerror('404',jstrans['Employment_Reports']['errors']['UIDINPUTNotFound']);}
            var uidvalue=elementExists.value;
            uidvalue=uidvalue.trim();
            if(uidvalue == ''){return showerror('404',jstrans['Employment_Reports']['errors']['UIDINPUTEmpty']);}
            var data={id:[uidvalue],"totalEntryCount": true,Section};
            var tableTemplate=dbTable;
            return {data,tableTemplate};
    }
    prepareTableName=function(Section){
        var elementExists = document.getElementById(Section);
        if(elementExists == null){return showerror('404',jstrans['Employment_Reports']['errors']['UIDINPUTNotFound']);}
        var namevalue=elementExists.value;
        namevalue=namevalue.trim();
        if(namevalue == ''){return showerror('404',jstrans['Employment_Reports']['errors']['UIDINPUTEmpty']);}
        var data={name:namevalue,"totalEntryCount": true,Section};
        var tableTemplate=dbTable;
        return {data,tableTemplate};
    }
    prepareTableGrievance=function(Section){
        startInput=GrievanceStart;
            EndInPut=GrievanceEnd;
            var tableTemplate=dbTable;
            
            var options=new Array();
            $.each($(startInput).prop("options"), function(i, opt) {
                options.push(parseInt(opt.value));
            })
            var StartIndex=$(options).index(parseInt($(startInput).val()));
            var EndIndex=$(options).index(parseInt($(EndInPut).val()))+1;
            var data={id:options.slice(StartIndex,EndIndex),"totalEntryCount": true,Section};
            return {data,tableTemplate};
    }
    prepareTableSeating=function(Section){
        startInput=SeatingStart;
            EndInPut=SeatingEnd;
            var tableTemplate=dbTable;
            
            var options=new Array();
            $.each($(startInput).prop("options"), function(i, opt) {
                options.push(parseInt(opt.value));
            })
            var StartIndex=$(options).index(parseInt($(startInput).val()));
            var EndIndex=$(options).index(parseInt($(EndInPut).val()))+1;
            var data={id:options.slice(StartIndex,EndIndex),"totalEntryCount": true,Section};
            return {data,tableTemplate};
    }
    //////////////////////////
    showTable=function(Section){
        //console.log($('.dbTable').append($(SeatingTable).html()));
        /*/////prepare for Section /////////////////*/
        var URL=api + "employmentReports/People";
        var data={};
        if(Section == Sections.annonce){
            var data=perpareTableAnnonce(Section);
        }else if(Section == Sections.nid){
            var data=perpareTablenid(Section);
        }else if(Section == Sections.uid){
            var data=perpareTableUid(Section);
        }else if(Section == Sections.name){
            var data=prepareTableName(Section);
        }else if(Section == Sections.Grievance){
            var data=prepareTableGrievance(Section);
        }else if(Section == Sections.Seating){
            var data=prepareTableSeating(Section);
        }
        if(data == undefined){return;}
        insertInToTable(data.data,data.tableTemplate,URL,Section);
    }
    gotoprint=function(){

    }
    getSelectedIds=function(e){
        var btn=e.currentTarget;
        var btnId=$(btn).id();
        $(updatearea).hide();
        $(printform).hide();
        $(downloadform).hide();
        $(SeatingForm).hide();
        if(btnId == 'uptodatecols'){
            var input=uptoidsTextarea;
            console.log(input);
            var data = $(input).val()
            dataIDS=JSON.parse(data);
            $(updatearea).show()
            bpFieldInitCKEditorElement($('textarea[name=editor1]'))    
        }else if(btnId == 'printcols'){
            $(printform).show();
        }else if(btnId == 'downloadcols'){
            $(downloadform).show();
            PostDownload();
        }else if(btnId == 'Seatingcols'){
            $(SeatingForm).show();
        }

    }
    function bpFieldInitCKEditorElement(element) {
        element.on('AmerField.deleted', function(e) {
          //alert("DS");
            $ck_instance_name = element.siblings("[id^='cke_editor']").attr('id');
            if($ck_instance_name.startsWith('cke_')) {
                $ck_instance_name = $ck_instance_name.substr(4);
            }
            CKEDITOR.instances[$ck_instance_name].destroy(true);
        });
        element.ckeditor(element.data('options'));
    }
    insertMessagetoTextArea=function(e){
        var elemst=$(e)[0];
        var elemst=elemst['currentTarget'];
        var value=$(elemst).attr('data-k');
        var LocaStorage=window.localStorage.getItem('message_template');
        var Templates=JSON.parse(LocaStorage);
        CKEDITOR.instances['editor1'].insertHtml(Templates[value]['html']);
        //CKEDITOR.instances['editor1'].setData(Templates[value]['html'])
    }
    showDegreesDiv=function(){
        $(new_Degrees_div).show();
        $(new_Degrees_div).html('');
        var oldstorage=JSON.parse(localStorage.getItem('selectedids'))
        $.each(oldstorage,function(k,v){
            var row=$('<div class="row"></div>')
            var colName=$('<div class="col-sm-2">'+v+'<input type="hidden" value="'+k+'" name="Degree_ids_'+k+'"></div>');
            var colTahriry=$('<div class="col-sm">'+jstrans['Employment_Reports']['UpToDateForm']['DegreeTahriry']+'</div><div class="col-sm"><input type="text" class="form-control" id="DegreeTahriry" name="DegreeTahriry_'+k+'" min="0" max="100"></div>')
            var colAmaly=$('<div class="col-sm">'+jstrans['Employment_Reports']['UpToDateForm']['DegreeAmaly']+'</div><div class="col-sm"><input type="text" class="form-control" id="DegreeAmaly" name="DegreeAmaly_'+k+'" min="0" max="100"></div>')
            var colMeeting=$('<div class="col-sm">'+jstrans['Employment_Reports']['UpToDateForm']['DegreeMeeting']+'</div><div class="col-sm"><input type="text" class="form-control" id="DegreeMeeting" name="DegreeMeeting_'+k+'" min="0" max="100"></div>')
            $(row).append(colName);$(row).append(colTahriry);$(row).append(colAmaly);$(row).append(colMeeting);
            $(new_Degrees_div).append($(row));
        })
    }
    showSelectMessageModel=function(){
        if($('#messageTemplatesModal').length !== 0){
            return $('#messageTemplatesModal').modal('show');
        }
        var LocaStorage=window.localStorage.getItem('message_template');
        var Templates=JSON.parse(LocaStorage);
        
        var $modal=`<div class="modal modal-xl fade" id="messageTemplatesModal" tabindex="-1" aria-labelledby="messageTemplatesModal" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="messageTemplatesModalLabel">`+jstrans['Employment_Reports']['UpToDateForm']['selectMessageTemplate']+`</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                </div>
            </div>
            <div class="modal-footer">
            </div>
          </div>
        </div>
      </div>`;
      $('body').append($modal);
      $.each(Templates,function(k,v){
        var shortMessages=$(`<div class="col-sm-6 btn btn-sm border" data-action="insertMessagetoTextArea" data-k="`+k+`">`+v['select']+`</div>`);
        $('#messageTemplatesModal .modal-dialog .modal-content .modal-body .row').append(shortMessages);
    })
      $('#messageTemplatesModal').modal('show');
    }
    setalltahriry=function(e,start){
        var temp=$(e)[0]['currentTarget'];
        var inputs=$('input[name*='+start+'_]');
        for(var i=0;i<inputs.length;i++){
            var input=inputs[i]
            if($(input).attr('name') == $(temp).attr('name')){
                var target_i=i;
            }
        }
        for(var i=target_i+1;i<=target_i+1;i++){
            var input=inputs[i]
            var targetvalue=$(temp).val();
            if($(input).val() == ''){
                $(input).val(targetvalue);
            }
        }
    }
    chooseDownloadActions=function(e){
        var val=e.currentTarget.value;
        if(val == 'Full'){
            if($('input[id=btn-check-outlined_Full]').is(":checked") === true){var checked="checked";}else{var checked="";}
            $.each($('input[id^=btn-check-outlined_]'),function(k,v){
                $(v).prop("checked",checked);
            });
            $('input[id=btn-check-outlined_Full]').prop("checked",checked);
        }else{
            if($('input[id=btn-check-outlined_Full]').is(":checked") === true){
                $('input[id=btn-check-outlined_Full]').prop("checked",'');
            }
        }
    }
    window.createSeatingTable=function(action,dataIDS){
        var form=$('form[name=SeatingForm]');
        if(window.Amer.idintifi.SeatingTable == null){
            $('.SeatingFormTable').html($(SeatingTableTemplate).html());
        }
        var ids=JSON.parse(SeatingidsTextarea.value);
        $.each(ids,function(k,v){
            var maintr=$('#AmerTable tbody').find('tr[data-id='+v+']')
            var fulldata=$(maintr).attr('data-full');
            fulldata=JSON.parse(fulldata);
            var fullname=fulldata.Fname +' '+fulldata.Sname +' '+fulldata.Tname +' '+fulldata.Lname
            var Seatings=fulldata.Seatings;
            console.log($(Seatings).length);
            var tr=$('<tr></tr>');
            $(tr).attr('data-id',v);
            var idTd=$('<td></td>');
            idTd.attr('data-id',v);idTd.attr('data-for','id');idTd.attr('data-value',v);idTd.text(v);
            var nidTd=$('<td></td>');
            nidTd.attr('data-id',v);nidTd.attr('data-for','nid');nidTd.attr('data-value',v);nidTd.text(fulldata.NID);
            var nameTd=$('<td></td>');
            nameTd.attr('data-id',v);nameTd.attr('data-for','fullName');nameTd.attr('data-value',v);nameTd.text(fullname);
            if($(Seatings).length == 0){

            }
            $(tr).append($(idTd));
            $(tr).append($(nidTd));
            $(tr).append($(nameTd));
            //var tr=$(window.Amer.idintifi.SeatingTableTrTemplate).html()
            //$(tr).attr('data-id',v);
            //var dataid=$(tr).find('[data-id]');
            //for(i=0;i<dataid.length;i++){
                //console.log(dataid[i]);
            //}
            //$.each(dataid,function(a,b){$(b).attr('data-id',v);});
            //console.log(tr);
            var tbody=$(SeatingTable).find('tbody');
            $(tbody).append($(tr));
            
            //tabletbodyappend

        });
        ///createTable;
        //window.Amer.idintifi.SeatingTableTrTemplate
    };
    setSelect2();
    $(AnnonceAnnonce).change(function(e) {
        removeallSelect2ElementsOptions(['AnnonceJob','AnnonceStart','AnnonceEnd']);
        $(byAnnonceShow).hide();
        window.set_job(AnnonceJob);
        window.setcount($(AnnonceAnnonce).id());
    });
    $(AnnonceStage).change(function(e) {
        removeallSelect2ElementsOptions(['AnnonceStart','AnnonceEnd']);
        $(byAnnonceShow).hide();
        window.setcount($(AnnonceAnnonce).id());
    });
    $(AnnonceJob).change(function(e) {
        removeallSelect2ElementsOptions(['AnnonceStart','AnnonceEnd']);
        $(byAnnonceShow).hide();
        window.setcount($(AnnonceAnnonce).id());
    });
    $(AnnonceStatus).change(function(e) {
        removeallSelect2ElementsOptions(['AnnonceStart','AnnonceEnd']);
        $(byAnnonceShow).hide();
        window.setcount($(AnnonceAnnonce).id());
    });
    $(AnnonceStart).change(function(e) {
        removeallSelect2ElementsOptions(['AnnonceEnd']);
        window.setEnd($(AnnonceAnnonce).id());
    });
    $(AnnonceEnd).change(function(e) {
        $(byAnnonceShow).show();
    });
    $(nidshow).click(function(e){
        showTable($(NIDINPUT).id())
    });
    //////////////////////////////
    $(GrievanceAnnonce).change(function(e) {
        removeAllOptions(document.getElementById('GrievanceJob'));
        removeAllOptions(document.getElementById('GrievanceStart'));
        removeAllOptions(document.getElementById('GrievanceEnd'));
        $(Grievanceshow).hide();
        window.set_job(GrievanceJob);
        window.setcount($(GrievanceAnnonce).id());
    });
    $(GrievanceJob).change(function(e) {
        removeAllOptions(document.getElementById('GrievanceStart'));
        removeAllOptions(document.getElementById('GrievanceEnd'));
        $(Grievanceshow).hide();
        window.setcount($(GrievanceAnnonce).id());
    });
    $(GrievanceType).change(function(e) {
        removeAllOptions(document.getElementById('GrievanceStart'));
        removeAllOptions(document.getElementById('GrievanceEnd'));
        $(Grievanceshow).hide();
        window.setcount($(GrievanceAnnonce).id());
    });
    $(GrievanceResult).change(function(e) {
        removeAllOptions(document.getElementById('GrievanceStart'));
        removeAllOptions(document.getElementById('GrievanceEnd'));
        $(Grievanceshow).hide();
        window.setcount($(GrievanceAnnonce).id());
    });
    $(GrievanceStart).change(function(e) {
        removeAllOptions(document.getElementById('GrievanceEnd'));
        $(Grievanceshow).show();
        window.setEnd($(GrievanceAnnonce).id());
    });
    $(GrievanceEnd).change(function(e) {
        $(Grievanceshow).show();
    });
    ////////////////////////////////////
    $(SeatingAnnonce).change(function(e) {
        removeallSelect2ElementsOptions(['SeatingJob','SeatingStart','SeatingEnd']);
        $(byAnnonceShow).hide();
        window.set_job(SeatingJob);
        window.setcount($(SeatingAnnonce).id());
    });
    $(SeatingJob).change(function(e) {
        removeallSelect2ElementsOptions(['SeatingStart','SeatingEnd']);
        $(Seatingshow).hide();
        window.setcount($(SeatingAnnonce).id());
    });
    $(SeatingStages).change(function(e) {
        removeallSelect2ElementsOptions(['SeatingStart','SeatingEnd']);
        $(Seatingshow).hide();
        window.setcount($(SeatingAnnonce).id());
    });
    ///////////////////////////////
    $(Filters).change(function(e) {
        removeallSelect2ElementsOptions(['FiltersRes','FiltersStart','FiltersEnd']);
        $(Filtersshow).hide();
        window.SetFilters(SeatingJob);
        //window.setcount($(SeatingAnnonce).id());
    });
    ////////////////////////////////
    $(nameShow).click(function(e){
        showTable($(NameINPUT).id())
    });
    $(uidshow).click(function(e){
        showTable($(UIDINPUT).id())
    });
    $(byAnnonceShow).click(function(e){
        showTable($(AnnonceAnnonce).id())
    });
    $(byAnnonceShow).click(function(e){
        showTable($(AnnonceAnnonce).id())
    });
    $(Grievanceshow).click(function(e){
        showTable($(GrievanceAnnonce).id())
    });
    $(Seatingshow).click(function(e){
        showTable($(SeatingAnnonce).id())
    });
    /*
    $(printcols).click(function(e){
        gotoprint();
    });*/
    $(document).on("click", "#uptodatecols", function(e) {

        getSelectedIds(e);
    });
    $(document).on("click", "#printcols", function(e) {
        getSelectedIds(e);
    });
    $(document).on("click", "#downloadcols", function(e) {
        getSelectedIds(e);
    });
    $(document).on("click", "#Seatingcols", function(e) {
        getSelectedIds(e);
    });
    window.setMessages();
    $('#addDegrees').on('click',function(){
        showDegreesDiv();        
    })
    $('#selectMessageTemplate').on('click',function(){
        showSelectMessageModel();
    })
    $(document).on('change','input[name*=DegreeMeeting_]',function(e){
        setalltahriry(e,'DegreeMeeting');
    })
    $(document).on('change','input[name*=DegreeTahriry_]',function(e){
        setalltahriry(e,'DegreeTahriry');
    })
    $(document).on('change','input[name*=DegreeAmaly_]',function(e){
        setalltahriry(e,'DegreeAmaly');
    })
    $(document).on('click','div[data-action=insertMessagetoTextArea]',function(e){
        insertMessagetoTextArea(e);
    })
    $('input[name=send]').on('click',function(){
        preparetoSend();
    });
    $('input[id^=btn-check-outlined_]').on('click',function(e){
        chooseDownloadActions(e);
    })
    if($('#SeatingPrintType').val() == 'Ticket'){$(forSeatingTablesSelect).hide();}else{$(forSeatingTablesSelect).show();}
    $('#SeatingPrintType').on('change',function(e){
        if($('#SeatingPrintType').val() == 'Ticket'){$(forSeatingTablesSelect).hide();}else{$(forSeatingTablesSelect).show();}
    })
    var hiddenelements=[PrintidsTextArea,updatearea,uptoidsTextarea,new_Degrees_div,SeatingForm,SeatingidsTextarea,forSeatingTablesSelect,Grievanceshow,updatearea,byAnnonceShow,printform];
$.each(hiddenelements,function(k,v){$(v).hide();});
})(jQuery)
$('Emp_PEO_').append($('<div id="text"></div>'));
