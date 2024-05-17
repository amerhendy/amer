(function() {
    const API_URL = api+'employment/checknid';
    var realnid = $('#nidreal');
    var sharedata=[
      'jobSlug','annonceSlug','formId','page'
    ];
check=function (jobSlug=null,annonceSlug=null,formId=null,nid,page) {
    var form=$('#'+formId);
    var serial=form.serializeArray();
    var data={};
    $.each(serial,function(k,v){
        data[v.name]=v.value;
    });
    data['annonceSlug']=window.Amer.annonceSlug;
    data['jobSlug']=window.Amer.jobSlug;
    data['nid']=nid;
    data['page']=page;
    jQuery.ajax({
        url: API_URL,
        method: 'POST',
        cache: false,
        dataType: 'json',
        data,
        contentType:'application/x-www-form-urlencoded',
        beforeSend:function(){
            beforesend('nid');
        },
    }).done(function(data) {
        if(page == 'create'){
            createDiv(data);
            window.Amer.NID=nid;
        }else if(page == 'showjob'){createDiv(data);window.Amer.NID=nid;}
        else if(page == 'apply'){
            window.Amer.NID=nid;
        }
    }).fail(function(jqXHR, textStatus) {
        window.Amer.NID=false;
        console.log(jqXHR, textStatus);
    });
}
createDiv=function(data){
    if(objkey_exists(data,'data')){
        var data=data.data;
        var result=data.result;
        var message=data.message;
    }
    if(objkey_exists(data,'message')){
        var data=data.message;
        var result=data.result;
        var message=data.message;
    }
    
    var e=$('input[name=nid][id=nid]')
    var form=$(e).closest('form');
    var btn = $('#savechanges');
    var btnlink= websitelink + "employment_operation/stage/" + window.Amer.annonceSlug + "/" + window.Amer.jobSlug;
    console.log(result);
    if(result == 'success'){
        realnid.html(message);
        btn.html($('#app_pro').html());
        $(form).append($(`<input type="hidden" name="annonce" value="${window.Amer.annonceSlug}">`))
        $(form).append($(`<input type="hidden" name="job" value="${window.Amer.jobSlug}">`))
        btn.show();$(form).attr('action',btnlink);
    }else{
        btn.hide();$(form).attr('action','');
    }
}
})(jQuery)