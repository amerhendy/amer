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
var NinInput = document.getElementById("nid");
var UidInput = document.getElementById("uid");
var NameInput = document.getElementById("name");
var AnnoneInput = document.getElementById('annonce');
var job_input = document.getElementById('job');
var stageInput = document.getElementById('stage');
var statusInput = document.getElementById('accept');
var AnnonceStartInPut=document.getElementById('start');
var AnnonceEndInPut=document.getElementById('end');
var ShowBtn=document.getElementById('byAnnonceShow');
var PrintBtn=document.getElementById('printcols');
var AnnonceTabletemplate=document.getElementById('dbTable');
var AnnonceTabletemplateTR=document.getElementById('dbTableTR');
var operations=$('#operations');
var updatearea=$('.updatearea');

var Annone2NDInput = document.getElementById('annonce_2nstage');

var NewStageInput = $('select[name=new_stage]');

var status2NDInput = document.getElementById('accept_2nstage');
var NewResultInput = $('select[name=new_res]');

var MessageTemplateInput = $('select[name=selectMessageTemplate]');
$(ShowBtn).hide();
$(PrintBtn).hide();
$(updatearea).hide();
$(operations).hide();
$('.printform').css('display', 'none');
$('#printcols').hide();
removeAllOptions(document.getElementById('annonce'));
removeAllOptions(document.getElementById('stage'));
removeAllOptions(document.getElementById('job'));
removeAllOptions(document.getElementById('accept'));
removeAllOptions(document.getElementById('start'));
removeAllOptions(document.getElementById('end'));
NinInput.value = '';
UidInput.value = '';
NameInput.value = '';
removeAllOptions(document.getElementById('annonce_2nstage'));
removeAllOptions(document.getElementById('job_2nstage'));
removeAllOptions(document.getElementById('accept_2nstage'));
removeAllOptions(document.getElementById('start_2nstage'));
removeAllOptions(document.getElementById('end_2nstage'));
removeAllOptions(document.getElementById('new_res'));
removeAllOptions(document.getElementById('new_stage'));
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
    setAcceptOptions=function() {
        var tag='Employment_Status';
        $('<option  />').html('').appendTo(statusInput);
        $('<option  />').html('').appendTo(status2NDInput);
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
                $('[data-bs-refresh='+$(statusInput).attr('id')+']').remove();
            },
            complete: function() {
                remove_loader_div();
            },
            success: function(data) {
                saveLocalData(tag,JSON.stringify(data.data));
                SetOptions(tag,data.data);
            },
            error: function(e, xhr, opt) {
                remove_loader_div();faileajax(statusInput,'setAcceptOptions');
                console.log("error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            }
        });
    };
    setStages=function() {
        var tag='Employment_Stages';
        $('<option value="0"/>').html("ايقاف").appendTo(stageInput);
        $('<option  />').html('').appendTo(NewStageInput);
        $('<option value="0"/>').html("ايقاف").appendTo(NewStageInput);
        if(getSavedLocalData(tag)){
            return SetOptions(tag,JSON.parse(getSavedLocalData(tag)));
        }
        var link = api + 'stages'
        jQuery.ajax({
            url: link,
            dataType: 'json',
            type: 'post',
            cache: true,
            crossDomain: true,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$(stageInput).attr('id')+']').remove();
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
                remove_loader_div();faileajax(stageInput,'setStages');
                console.log("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            }
        });
    };
    setAnnNames=function(){
        var tag='Employment_StartAnnonces'
        if(getSavedLocalData(tag)){
            //return SetOptions(tag,JSON.parse(getSavedLocalData(tag)));
        }
        var link = api + 'Annonces'
        jQuery.ajax({
            url: link,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$(AnnoneInput).attr('id')+']').remove();
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
                remove_loader_div();faileajax(AnnoneInput,'setAnnNames');
                console.log("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            }
        });
    };
    set_job=function(e){
        var elementId=$(e).id();
        if(elementId == 'job'){
            var AnnonceVal=$(AnnoneInput).val();
        }
        if(AnnonceVal == '')return;
        $('<option value=""/>').html('').appendTo($(e));
        var tag='Employment_Jobs_'+elementId+'_'+AnnonceVal;
        if(getSavedLocalData(tag)){
            //return SetOptions(tag,JSON.parse(getSavedLocalData(tag)));
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
            data:data,
            cache: true,
            crossDomain: true,
            success: function(data) {
                //saveLocalData(tag,JSON.stringify(data.data));
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
                $('<option value="' + item['id'] + '"/>').html(item['Number'] + ' / ' + item['Year']).appendTo(AnnoneInput);
                $('<option value="' + item['id'] + '"/>').html(item['Number'] + ' / ' + item['Year']).appendTo(Annone2NDInput);
            });
        }
        if(tag == 'Employment_Stages')
        {
            data.forEach(function(item, k) {
                $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(stageInput);
                $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(NewStageInput);
            });
        }
        if(tag == 'Employment_Status')
        {
            data.forEach(function(item, k) {
                $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(statusInput);
                $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(status2NDInput);
                $('<option value="' + item['id'] + '"/>').html(item['Text']).appendTo(NewResultInput);
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
            $('<option value="">').html('').appendTo($(value));
            $(value).select2();
            var functionName=$(value).data('set-select2');
            if (typeof window[functionName] === "function") {
                window[functionName](value);
            }
        });
    };
    setcount=function(section){
        if(section == 'annonce'){
            var data={
                        'Annonce_id':$(AnnoneInput).val(),
                        'Job_id':$(job_input).val(),
                        'Stage_id':$(stageInput).val(),
                        'Status_id':$(statusInput).val(),
                    };
        }
        link=api+'employmentReports/Counts'
        jQuery.ajax({
            url: link,
            beforeSend: function() {
                loader_div();
                $('[data-bs-refresh='+$(AnnoneInput).attr('id')+']').remove();
            },
            complete: function() {
                remove_loader_div();
            },
            dataType: 'json',
            type: 'post',
            data:data,
            cache: true,
            crossDomain: true,
            success: function(data) {
                $('#length').html(data.recordsTotal);
                setStart(section,data.data);
            },
            error: function(e, xhr, opt) {
                remove_loader_div();faileajax(AnnoneInput,'setcount');
                console.log("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            },
            statusCode: {
                402: function() {
                  alert( "page not found" );
                }
              }
        });
    }
    setStart=function(section,data){
        if(section == 'annonce'){
            var startInput=AnnonceStartInPut;
            var EndInPut=AnnonceEndInPut;
        }
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
    }
    setEnd=function(section){
        if(section == 'annonce'){
            startInput=AnnonceStartInPut;
            EndInPut=AnnonceEndInPut;
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
    //////////////////////////
    showTable=function(Section){
        if(Section == 'annonce'){
            startInput=AnnonceStartInPut;
            EndInPut=AnnonceEndInPut;
            var tableTemplate=AnnonceTabletemplate;
            var trTemplate=AnnonceTabletemplateTR;
        }
        var options=new Array();
        $.each($(startInput).prop("options"), function(i, opt) {
            options.push(parseInt(opt.value));
        })
        var StartIndex=$(options).index(parseInt($(startInput).val()));
        var EndIndex=$(options).index(parseInt($(EndInPut).val()))+1;
        var data={id:options.slice(StartIndex,EndIndex),"totalEntryCount": true};
        var URL=api + "employmentReports/People";
        insertInToTable(data,tableTemplate,URL,Section);
    }
    gotoprint=function(){

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
            <div class="col-sm-3">`+jstrans['stage']+`</div><div class="col-sm-9">`+data.Text+`</div>
            <div class="col-sm-3">`+jstrans['stageresult']+`</div><div class="col-sm-9">`+data.Result+`</div>
            <div class="col-sm-3">`+jstrans['stageMessage']+`</div><div class="col-sm-9">`+data.Message+`</div>
            <div class="col-sm-3">`+jstrans['stageDate']+`</div><div class="col-sm-9">`+data.created_at+`</div>
        </div>
        `;
        showerror(uid+": "+fullname,html,'info');
    }
    getSelectedIds=function(e){
        var input=$('textarea[name=uptoids]');
        var data = $(input).val()
        dataIDS=JSON.parse(data);
        $('.updatearea').show()
        bpFieldInitCKEditorElement($('textarea[name=editor1]'))
    }
    function bpFieldInitCKEditorElement(element) {
        element.on('AmerField.deleted', function(e) {
          alert("DS");
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
        $('#new_Degrees_div').show();
        $('#new_Degrees_div').html('');
        var oldstorage=JSON.parse(localStorage.getItem('selectedids'))
        $.each(oldstorage,function(k,v){
            var row=$('<div class="row"></div>')
            var colName=$('<div class="col-sm-2">'+v+'<input type="hidden" value="'+k+'" name="Degree_ids_'+k+'"></div>');
            var colTahriry=$('<div class="col-sm">'+jstrans['DegreeTahriry']+'</div><div class="col-sm"><input type="text" class="form-control" id="DegreeTahriry" name="DegreeTahriry_'+k+'" min="0" max="100"></div>')
            var colAmaly=$('<div class="col-sm">'+jstrans['DegreeAmaly']+'</div><div class="col-sm"><input type="text" class="form-control" id="DegreeAmaly" name="DegreeAmaly_'+k+'" min="0" max="100"></div>')
            var colMeeting=$('<div class="col-sm">'+jstrans['DegreeMeeting']+'</div><div class="col-sm"><input type="text" class="form-control" id="DegreeMeeting" name="DegreeMeeting_'+k+'" min="0" max="100"></div>')
            $(row).append(colName);$(row).append(colTahriry);$(row).append(colAmaly);$(row).append(colMeeting);
            $('#new_Degrees_div').append($(row));
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
              <h1 class="modal-title fs-5" id="messageTemplatesModalLabel">`+jstrans['selectMessageTemplate']+`</h1>
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
        var shortMessages=$(`<div class="col-sm-12 btn" data-action="insertMessagetoTextArea" data-k="`+k+`">`+v['select']+`</div>`);
        $('#messageTemplatesModal .modal-dialog .modal-content .modal-body').append(shortMessages);
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
    setSelect2();
    $(AnnoneInput).change(function(e) {
        removeAllOptions(document.getElementById('job'));
        removeAllOptions(document.getElementById('start'));
        removeAllOptions(document.getElementById('end'));
        $(ShowBtn).hide();
        window.set_job(job_input);
        window.setcount($(AnnoneInput).id());
    });
    $(stageInput).change(function(e) {
        removeAllOptions(document.getElementById('start'));
        removeAllOptions(document.getElementById('end'));
        $(ShowBtn).hide();
        window.setcount($(AnnoneInput).id());
    });
    $(job_input).change(function(e) {
        removeAllOptions(document.getElementById('start'));
        removeAllOptions(document.getElementById('end'));
        $(ShowBtn).hide();
        window.setcount($(AnnoneInput).id());
    });
    $(statusInput).change(function(e) {
        removeAllOptions(document.getElementById('start'));
        removeAllOptions(document.getElementById('end'));
        $(ShowBtn).hide();
        window.setcount($(AnnoneInput).id());
    });
    $(AnnonceStartInPut).change(function(e) {
        removeAllOptions(document.getElementById('end'));
        $(ShowBtn).hide();
        window.setEnd($(AnnoneInput).id());
    });
    $(AnnonceEndInPut).change(function(e) {
        $(ShowBtn).show();
    });
    $(ShowBtn).click(function(e){
        showTable($(AnnoneInput).id())
    });
    $(PrintBtn).click(function(e){
        gotoprint();
    });
    $(document).on("click", "#uptodatecols", function() {
        getSelectedIds();
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
})(jQuery)
$('Emp_PEO_').append($('<div id="text"></div>'));
