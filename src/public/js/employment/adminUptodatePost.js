(function(){
$.ajaxSetup({ cache: true });
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
const DownloadMinutes=15;
checkNANInputs=function(){
    var wantedinputs=['_token','uptoidsTextarea','publisher','new_stage','new_res','editor1'];
    var dataarr=$('form[name=uptodateform]').serializeArray();
    var errors=new Array,arrnames=new Array();
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        arrnames.push(obj['name']);
    }
    wantedinputs.forEach(element => {
        if(in_array(arrnames,element) == false)
        errors.push(element);
    });
    return errors;
}
checkToken=function(dataarr){
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(objName == '_token'){
            if(obj['value'] !== $('meta[name="csrf-token"]').attr('content')){return 'error';}else{return 'success';}
        }
    }
}
checkUIds=function(dataarr){
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(objName == 'uptoidsTextarea'){
            //if(obj['value'] !== $('textarea[name=uptoidsTextarea]').val()){return showerror("Error",'_token Bad');}
            var oldstorage=object_keys(JSON.parse(localStorage.getItem('selectedids'))).sort();
            var value=JSON.parse(obj['value']);var value=value.sort();
            var textarea=JSON.parse($('textarea[name=uptoidsTextarea]').val()).sort();
            if(compareArrays(oldstorage,value) == false){return 'error'}
            if(compareArrays(oldstorage,textarea) == false){return 'error'}
            if(compareArrays(value,textarea) == false){return 'error'}
            return value;
        }
    }
}
checkPublisher=function(dataarr){
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(objName == 'publisher'){
            if(isNumeric(obj['value']) == false ){return 'error'}
        }
    }
}
checkNewStage=function(dataarr){
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(objName == 'new_stage'){
            if(obj['value'] == ''){return 'error';}
            var keys=objValueArr(JSON.parse(localStorage.getItem('Employment_Stages')),'id');
            if(in_array(keys,parseInt(obj['value'])) === false){
                if(parseInt(obj['value']) !== 0){
                    return 'error';
                }
            }
        }
    }
}
checkNewStatus=function(dataarr){
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(objName == 'new_res'){
            if(obj['value'] == ''){
                return 'error';
            }
            var keys=objValueArr(JSON.parse(localStorage.getItem('Employment_Status')),'id');
            if(in_array(keys,parseInt(obj['value'])) === false){
                return 'error';
            }
        }
    }
}
checkMessage=function(dataarr){
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(objName == 'editor1'){
            if(obj['value'] == ''){
                return'error';
            }
        }
    }
}
uIdsIntegration=function(dataarr,ids){
    var idS=new Array(); Degree_ids=new Array(), DegreeTahriry=new Array(), DegreeAmaly=new Array(), DegreeMeeting=new Array();
    $.each(ids,function(k,v){
        idS.push({id:v});
    })  
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(startwith(objName,'DegreeTahriry')){
            var id=split_text(objName,'DegreeTahriry_')[1];
            var index=objArrsKeyByArrKey(idS,'id',id);
                if(obj['value'] !== ''){
                    if(isNumeric(obj['value'])){
                        obj['value']=parseFloat(obj['value']);
                        idS[index]['Tahriry']=obj['value'];
                    }
                }
        }
        if(startwith(objName,'DegreeAmaly')){
            var id=split_text(objName,'DegreeAmaly_')[1];
            var index=objArrsKeyByArrKey(idS,'id',id);
                if(obj['value'] !== ''){
                    if(isNumeric(obj['value'])){
                        obj['value']=parseFloat(obj['value']);
                        idS[index]['Amaly']=obj['value'];
                    }
                }
        }
        if(startwith(objName,'DegreeMeeting')){
            var id=split_text(objName,'DegreeMeeting_')[1];
            var index=objArrsKeyByArrKey(idS,'id',id);
                if(obj['value'] !== ''){
                    if(isNumeric(obj['value'])){
                        obj['value']=parseFloat(obj['value']);
                        idS[index]['Meeting']=obj['value'];
                    }
                }
        }
    }
    return idS;
}
unsetDegrees=function(dataarr){
    var degrresIds=new Array();
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(startwith(objName,'Degree_ids')){
            dataarr.splice(i,1);
        }
    }
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(startwith(objName,'DegreeTahriry')){
            dataarr.splice(i,1);
        }
    }
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(startwith(objName,'DegreeAmaly')){
            dataarr.splice(i,1);
        }
    }
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(startwith(objName,'DegreeMeeting')){
            dataarr.splice(i,1);
        }
    }
    return dataarr;
}
preparetoSend=function(){
    var errors=checkNANInputs();
    if(errors.length !== 0){
        errors.forEach(element => {
           showerror("Error",'Some elements not found');
        });
        return;
    }
    var dataarr=$('form[name=uptodateform]').serializeArray();
    var errors=new Array,arrnames=new Array(),UIDS;
    if(checkToken(dataarr) == 'error'){return showerror("Error",'_token Bad');}
    if(checkPublisher(dataarr) == 'error'){return showerror("Error",'Select Publisher');}
    var UIDS=checkUIds(dataarr);
    if(UIDS == 'error'){return showerror("Error",'Please Select Right Users');}
    if(checkNewStage(dataarr) == 'error'){return showerror("Error",jstrans['stage']);}
    
    if(checkNewStatus(dataarr) == 'error'){return showerror("Error",jstrans['Employment_Reports']['UpToDateForm']['addUserToStatus']);}
    if(checkMessage(dataarr) == 'error'){return showerror("Error",jstrans['Employment_Reports']['UpToDateForm']['messageText']);}
    var UIDS=uIdsIntegration(dataarr,UIDS);
    unsetDegrees(dataarr);
    var uptoidsIndex=MultidimentionsArray_search(dataarr,'name','uptoidsTextarea');
    dataarr[uptoidsIndex]={'name':'uptoidsTextarea',value:JSON.stringify(UIDS)};
    var URL = api + "employmentReports/adminuptodate";
    var SendInput=$('#send');
    jQuery.ajax({
        url: URL,
        dataType: 'json',
        contentType:'application/x-www-form-urlencoded',
        type: 'post',
        cache: true,
        data:dataarr,   
        crossDomain: true,
        beforeSend: function() {
            loader_div();
            $('[data-bs-refresh='+$(SendInput).attr('id')+']').remove();
        },
        complete: function() {
            remove_loader_div();
        },
        success: function(data) {
            if(isObjext(data)){
                createLog(data,UIDS);
            }
        },
        error: function(e, xhr, opt) {
            ResponseError(e);
            remove_loader_div();faileajax(SendInput,'setAcceptOptions');
            console.log("error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        }
    });    
}
createLog=function(data,uids)
{
    if(!objkey_exists(data,'data')){log(data);return ;}
    data=data.data;
    if(typeof data == 'string'){
        if(startwith(data,'Content-Type: application/pdf;')){
            var responsive=$('<div class="container"></div>');
            var  section=$('#jobInfoSection');
            var file= new Blob([data],{type:'application/pdf'});
            var st=data.split(';\r\n');
            var st=st[2].split('\r\n\r\n')
            var iframe= document.createElement('iframe');
            $(iframe).attr('style','top:0; left:0; bottom:0; right:0; width:100%; height:21cm; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;');
            //section.html(iframe);
            iframe.src="data:application/pdf;base64,"+st[1]
            responsive.append($(iframe));
            showLogModel(responsive);
        }
    }
    return;
    if(data[0]['Degree'] == undefined){var degreeSett=false;}else{var degreeSett=true;}
    var responsive=$('<div class="table-responsive"></div>');
    var table=$('<table class="table table-striped table-hover table-bordered table-sm"></table>');
    var caption=$('<caption>'+jstrans['Employment_Reports']['UpToDateForm']['LogMessages']+'</caption>');
    responsive.append(table)
    table.html(caption);
    var thead=$('<thead class=""></thead>');
    if(degreeSett === true){
        var thdeg=`
        <th scope="col">`+jstrans['Employment_Reports']['UpToDateForm']['DegreeTahriry']+`</th>
        <th scope="col">`+jstrans['Employment_Reports']['UpToDateForm']['DegreeAmaly']+`</th>
        <th scope="col">`+jstrans['Employment_Reports']['UpToDateForm']['DegreeMeeting']+`</th>`;
    }else{var thdeg='';}
    var theadContent=$(`<tr>
    <th scope="col">#</th>
    <th scope="col">`+jstrans['FULLname']+`</th>
    <th scope="col">`+jstrans['Employment_Reports']['UpToDateForm']['newStage']+`</th>
    ${thdeg}
    </tr>`);
    thead.html(theadContent)
    table.append(thead);
    var tbody=$('<tbody></tbody>');
    $.each(uids,function(k,v){
        var tr=$('<tr></tr>');
        var uid=v['id'];
        var uidtr=$('<th>'+uid+'</th>');
        var localstorage=JSON.parse(localStorage.getItem('selectedids'));
        var fullname=localstorage[uid];
        var fullnametd=$('<td>'+fullname+'</td>');
        tr.append(uidtr);
        tr.append(fullnametd);
        var keyresult=objArrsKeyByArrKey(data,'Uid',uid);
        var errorsKeys=object_keys(data[keyresult]['errors']);
        if(errorsKeys === false){
            var stageresult='<i class="text-success fa fa-check" aria-hidden="true"></i>';
        }else{
            var stageresult='<i class="text-danger fa fa-exclamation-triangle" aria-hidden="true"></i>';
        }
        var stageTd=$('<td>'+stageresult+'</td>')
        if(degreeSett === true){
            if(exists (v['Tahriry']) == false){
                var tahriryResult='<i class="text-warning fa fa-ban" aria-hidden="true"></i>';
            }else{
                if(in_array(errorsKeys,'degree')){
                    var tahriryResult='<i class="text-danger fa fa-exclamation-triangle" aria-hidden="true"></i>';
                }else{
                    var tahriryResult='<i class="text-success fa fa-check" aria-hidden="true"></i>';
                }
            }
            if(exists (v['Amaly']) == false){
                var AmalyResult='<i class="text-warning fa fa-ban" aria-hidden="true"></i>';
            }else{
                if(in_array(errorsKeys,'degree')){
                    var AmalyResult='<i class="text-danger fa fa-exclamation-triangle" aria-hidden="true"></i>';
                }else{
                    var AmalyResult='<i class="text-success fa fa-check" aria-hidden="true"></i>';
                }
            }
            if(exists (v['Meeting']) == false){
                var MeetingResult='<i class="text-warning fa fa-ban" aria-hidden="true"></i>';
            }else{
                if(in_array(errorsKeys,'degree')){
                    var MeetingResult='<i class="text-danger fa fa-exclamation-triangle" aria-hidden="true"></i>';
                }else{
                    var MeetingResult='<i class="text-success fa fa-check" aria-hidden="true"></i>';
                }
            }
            var tahriryTd=$('<td>'+tahriryResult+'</td><td>'+AmalyResult+'</td><td>'+MeetingResult+'</td>')
        }
        tr.append(stageTd);
        if(degreeSett === true){tr.append(tahriryTd);}
        
        //stage
        tbody.append(tr);
    })
    table.append(tbody);
    var infodiv=`<div class="row">
                    <div class="col-sm-1"><i class="text-success fa fa-check" aria-hidden="true"></i></div>
                    <div class="col-sm-2">تم التعديل</div>
                    <div class="col-sm-1"><i class="text-danger fa fa-exclamation-triangle" aria-hidden="true"></i></div>
                    <div class="col-sm-2">خطأ</div>
                    <div class="col-sm-1"><i class="text-warning fa fa-ban" aria-hidden="true"></i></div>
                    <div class="col-sm-2">غير مطلوب</div>
                    
                    </div>`;
    responsive.append($(infodiv));
    showLogModel(responsive);
}
showLogModel=function(html){
    if($('#ResultLogModal').length !== 0){
        $('#ResultLogModal .modal-dialog .modal-content .modal-body').html(html);
        return $('#ResultLogModal').modal('show');
    }
    var $modal=`<div class="modal modal-xl fade" id="ResultLogModal" tabindex="-1" aria-labelledby="ResultLogModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="ResultLogModalLabel">`+jstrans['LogMessages']+`</h1>
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
  $('#ResultLogModal .modal-dialog .modal-content .modal-body').append(html);
    $('#ResultLogModal').modal('show');
}
PostDownload=function(){
    var oldstorage=object_keys(JSON.parse(localStorage.getItem('selectedids'))).sort();
    var dataarr={ids:JSON.stringify(oldstorage),'Minutes': DownloadMinutes};
    var URL = api + "employmentReports/adminuptodate/downloadZip";
    var SendInput=$('#downloadcols');
    jQuery.ajax({
        url: URL,
        dataType: 'json',
        contentType:'application/x-www-form-urlencoded',
        type: 'post',
        cache: true,
        data:dataarr,   
        crossDomain: true,
        beforeSend: function() {
            loader_div();
            $('[data-bs-refresh='+$(SendInput).attr('id')+']').remove();
        },
        complete: function() {
            remove_loader_div();
        },
        success: function(data) {
            if(isObjext(data)){
                createDownloadList(data);
            }
        },
        error: function(e, xhr, opt) {
            remove_loader_div();
            
            faileajax(SendInput,'setAcceptOptions');
            console.log("error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        }
    });    
}
createDownloadList=function(data){
    var oldstorage=JSON.parse(localStorage.getItem('selectedids'));
    var uids=object_keys(JSON.parse(localStorage.getItem('selectedids')));
    var responsive=$('<div class="table-responsive"></div>');
    var table=$('<table class="table table-striped table-hover table-bordered table-sm"></table>');
    var caption=$('<caption>'+jstrans['Employment_Reports']['UpToDateForm']['DownloadList']+'</caption>');
    responsive.append(table)
    table.html(caption);
    var thead=$('<thead class=""></thead>');
    var theadContent=$(`<tr>
    <th scope="col">#</th>
    <th scope="col">`+jstrans['FULLname']+`</th>
    <th scope="col">`+jstrans['Employment_Reports']['UpToDateForm']['Download']+`</th>
    </tr>`);
    thead.html(theadContent)
    table.append(thead);
    var tbody=$('<tbody></tbody>');
    var newDataList=new Array();
    var newnotdownload=new Array();
    $.each(uids,function(k,v){
        var uid=v;
        //{3:{link:link,name:name}}}
        var los={};
        var pos={};
        if(data[v]){
            los['link']=data[v];
            los['name']=oldstorage[v];
            pos[v]=los;
            newDataList.push(pos);
            //console.log(data[v]);
            //newDataList.append(data[v]);
        }else{
            los['link']=null;
            los['name']=oldstorage[v];
            pos[v]=los;
            newnotdownload.push(pos);
        }
        
    });
    $.each(newDataList,function(k,v){
        var tr=$('<tr></tr>');
        var uid=object_keys(v)[0];
        var fullname=v[uid]['name'];
        var downloadLink=v[uid]['link'];
        var uidtr=$('<th>'+uid+'</th>');
        var fullnametr=$('<td>'+fullname+'</td>');
        var downloadtr=$('<td></td>');
        var downloadlinkhref=$('<a class="btn btn-sm" href="'+downloadLink+'" target="_blank"></a>');
        var downloadlinkicon=$('<i class="text-success fa fa-download"></i>');
        tr.append(uidtr);
        tr.append(fullnametr);
        tr.append(downloadtr);
        downloadtr.append(downloadlinkhref);
        downloadlinkhref.append(downloadlinkicon);
        tbody.append(tr);
    });
    $.each(newnotdownload,function(k,v){
        var tr=$('<tr></tr>');
        var uid=object_keys(v)[0];
        var fullname=v[uid]['name'];
        var downloadLink=v[uid]['link'];
        var uidtr=$('<th>'+uid+'</th>');
        var fullnametr=$('<td>'+fullname+'</td>');
        var downloadtr=$('<td></td>');
        var downloadlinkicon=$('<i class="text-danger fa fa-exclamation-triangle"></i>');
        tr.append(uidtr);
        tr.append(fullnametr);
        tr.append(downloadtr);
        downloadtr.append(downloadlinkicon);
        tbody.append(tr);
    });
    table.append(tbody);
    var infodiv=`<div class="row">
                    <div class="col-sm-1"><i class="text-success fa fa-download" aria-hidden="true"></i></div>
                    <div class="col-sm-2">`+jstrans['Employment_Reports']['UpToDateForm']['DownloadIcon']+`</div>
                    <div class="col-sm-1"><i class="text-danger fa fa-exclamation-triangle" aria-hidden="true"></i></div>
                    <div class="col-sm-2">`+jstrans['Employment_Reports']['UpToDateForm']['DownloadBanIcon']+`</div>
                    
                    </div>`;
    responsive.append($(infodiv));
    showDownloadList(responsive);
}

showDownloadList=function(html){
    
    if($('#DownloadListModal').length !== 0){
        $('#DownloadListModal .modal-dialog .modal-content .modal-body').append(html);
        return $('#DownloadListModal').modal('show');
    }
    var $modal=`<div class="modal modal-xl fade" id="DownloadListModal" tabindex="-1" aria-labelledby="DownloadListModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="DownloadListModalLabel">`+jstrans['Employment_Reports']['UpToDateForm']['LogMessages']+`</h1>
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
  $('#DownloadListModal .modal-dialog .modal-content .modal-body').append(html);
    $('#DownloadListModal').modal('show');
}
})(jQuery)