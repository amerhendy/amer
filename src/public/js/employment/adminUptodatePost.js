(function(){
$.ajaxSetup({ cache: true });
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
checkNANInputs=function(){
    var wantedinputs=['_token','uptoids','publisher','new_stage','new_res','editor1']
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
        if(objName == 'uptoids'){
            //if(obj['value'] !== $('textarea[name=uptoids]').val()){return showerror("Error",'_token Bad');}
            var oldstorage=object_keys(JSON.parse(localStorage.getItem('selectedids'))).sort();
            var value=JSON.parse(obj['value']);var value=value.sort();
            var textarea=JSON.parse($('textarea[name=uptoids]').val()).sort();
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
    var Degree_ids=new Array(), DegreeTahriry=new Array(), DegreeAmaly=new Array(), DegreeMeeting=new Array();
    for(i=0;i<dataarr.length;i++){
        var obj=dataarr[i];
        var objName=obj['name'];
        if(startwith(objName,'Degree_ids')){
            var id=split_text(objName,'Degree_ids_')[1];
            var index=array_search(ids,id);
            ids[index]={};
            ids[index]['id']=id;
        }
        if(startwith(objName,'DegreeTahriry')){
            var id=split_text(objName,'DegreeTahriry_')[1];
            var index=MultidimentionsArray_search(ids,'id',id);
            if(isObjext(ids[index]) == false){
                ids[index]={};
                ids[index]['id']=id;
            }
                if(obj['value'] !== ''){
                    if(isNumeric(obj['value'])){
                        obj['value']=parseFloat(obj['value']);
                        ids[index]['Tahriry']=obj['value'];
                    }
                }
        }
        if(startwith(objName,'DegreeAmaly')){
            var id=split_text(objName,'DegreeAmaly_')[1];
            var index=MultidimentionsArray_search(ids,'id',id);
            if(isObjext(ids[index]) == false){
                ids[index]={};
                ids[index]['id']=id;
            }
                if(obj['value'] !== ''){
                    if(isNumeric(obj['value'])){
                        obj['value']=parseFloat(obj['value']);
                        ids[index]['Amaly']=obj['value'];
                    }
                }
        }
        if(startwith(objName,'DegreeMeeting')){
            var id=split_text(objName,'DegreeMeeting_')[1];
            var index=MultidimentionsArray_search(ids,'id',id);
            if(isObjext(ids[index]) == false){
                ids[index]={};
                ids[index]['id']=id;
            }
                if(obj['value'] !== ''){
                    if(isNumeric(obj['value'])){
                        obj['value']=parseFloat(obj['value']);
                        ids[index]['Meeting']=obj['value'];
                    }
                }
        }
    }
    return ids;
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
    if(checkNewStatus(dataarr) == 'error'){return showerror("Error",jstrans['stageresult']);}
    if(checkMessage(dataarr) == 'error'){return showerror("Error",jstrans['stageMessage']);}
    var UIDS=uIdsIntegration(dataarr,UIDS);
    unsetDegrees(dataarr);
    var uptoidsIndex=MultidimentionsArray_search(dataarr,'name','uptoids');
    dataarr[uptoidsIndex]={'name':'uptoids',value:JSON.stringify(UIDS)};
    console.log(UIDS,dataarr);
    var URL = api + "employmentReports/adminuptodate";
    var SendInput=$('#send');
    jQuery.ajax({
        url: URL,
        dataType: 'json',
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
            console.log(data);
        },
        error: function(e, xhr, opt) {
            remove_loader_div();faileajax(SendInput,'setAcceptOptions');
            console.log("error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        }
    });    
}
})(jQuery)

