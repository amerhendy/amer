
(function(){
    //enable cache
    $.ajaxSetup({ cache: true });
    fetch('https://api.geoapify.com/v1/ipinfo?apiKey=0db4c89c994a467b82e450b5bb19695b')
    .then(response => response.json())
    .then(data => {
      var userData={};
      userData['city']=data.city.name;
      userData['continent']=data.continent.name;
      userData['country']=data.country.name;
      userData['ip']=data.ip;
      userData['location']=data.location;
      userData['state']=data.state.name;
      window.Amer.locationData=btoa(JSON.stringify(userData));
    })
    // set laravel csrf-token
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    //ad refresh on error
    faileajax=function(e,FN){
        par=$(e).parent();
        ref=$('<i class="fa fa-refresh text-success" aria-hidden="true" data-bs-refresh="'+$(e).attr('id')+'"></i>')
        $(par).append($(ref))
        $(ref).on('click',function(){
            window[FN](e)
        });
    };
    //remove refresh on reload
    beforesend=function(e){
        $('[data-bs-refresh='+$(e).attr('id')+']').remove();
    }
    //get client info
    var clientinfo=JSON.parse(localStorage.getItem('clientInfo'));
    }
)(jQuery);
//set client info
if(localStorage.getItem("clientInfo") == null){
    los=atob(clientInfo);
    los=JSON.parse(los);
    client_id=atob(los[0]);
    client_secret=atob(los[1]);
    var url = websitelink+"oauth/token";
    $.ajax({
        url:url, 
        async :false,
        contentType :'application/json',
        crossDomain :false,
        data:JSON.stringify({"grant_type": "client_credentials",'client_id':client_id,'client_secret':client_secret}),
        dataType :'json',
        method :'post',
    }).done(function(json){
        //var json = JSON.parse(json);
        var endtime = new Date();
            endtime.setSeconds(endtime.getSeconds() + json.expires_in);
            endtime=UnixTime(endtime,'to');
            var token_type=json.token_type;
            var token=json.access_token;
            localStorage.setItem('clientInfo',JSON.stringify({endtime,token_type,token}));
            location.reload();
            
    }).fail(function(jqXHR, textStatus){
        showerror('Error',jqXHR);
        console.log(jqXHR, textStatus);
    });
    /*
    return;
    var xhr = new XMLHttpRequest();
    
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
    xhr.setRequestHeader("Accept",'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            var endtime = new Date();
            endtime.setSeconds(endtime.getSeconds() + json.expires_in);
            endtime=UnixTime(endtime,'to');
            var token_type=json.token_type;
            var token=json.access_token;
            localStorage.setItem('clientInfo',JSON.stringify({endtime,token_type,token}));
            location.reload();
            return true;
        }else{
            console.log(xhr,xhr.status);
            showerror('error',xhr.responseText);
            alert(
                xhr.responseText
            );
        }
    };
    var data = JSON.stringify({"grant_type": "client_credentials",'client_id':client_id,'client_secret':client_secret});
    xhr.send(data);*/
}
$.xhrPool = [];
$.xhrPool.abortAll = function() {
    $(this).each(function(idx, jqXHR) {
        jqXHR.abort();
    });
    $.xhrPool = [];
};
function JqueryajaxHeaderData(json){
    json=JSON.parse(json);
    return JSON.stringify({'Authorization':'Bearer '+json.token});
}
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Vary':'Authorization',
        'Authorization':JSON.parse(localStorage.getItem("clientInfo"))['token']
    },
    dataType:"json",
    contentType:'application/json',
    data:JqueryajaxHeaderData(localStorage.getItem("clientInfo")),
    //data:localStorage.getItem("clientInfo"),
    beforeSend: function(jqXHR) {
        $('#loader').removeAttr('hidden');
        $.xhrPool.push(jqXHR);
    },
    success: function() {},
    error: function(jqXHR, textStatus, errorThrown) {
        ResponseError(jqXHR);
    },
    complete: function(jqXHR) {
        $('#loader').attr('hidden', 'hidden');
        var index = $.xhrPool.indexOf(jqXHR);
        if (index > -1) {
            $.xhrPool.splice(index, 1);
        }
    }
});
function ResponseError(e){
    if(isObjext(e.responseJSON)){
        var js=e.responseJSON;
        if(objkey_exists(js,'message')){
            if(isObjext(e.responseJSON.message)){
                js=e.responseJSON.message;
                if(objkey_exists(js,'message')){
                    js=js.message
                    if(typeof js !== 'object'){showerror('',js);console.log(e.responseJSON); return;}
                    html="";
                    $.each(js,function(k,v){
                        if(k == 'nid' || k =='NID'){
                            //get parent
                            var pare=$('input[name='+k+']').parent().children();
                            for(i=0;i<=pare.length -1;i++){
                                if($(pare[i]).id() == 'nidreal'){
                                    $(pare[i]).text(v.join('<br>'));
                                }
                            }
                        }else{
                            if($('input[name='+k+']')){
                                $('input[name='+k+']').addClass('is-invalid');
                                if($($('input[name='+k+']').next()[0]).attr('for') == k){
                                    $($('input[name='+k+']').next()[0]).remove()
                                }
                                $('input[name='+k+']').after('<i class="text-danger" for="'+k+'">'+v.join('<br>')+'</i>');
                            }
                        }
                        
                        html+=v.join('<br>');
                    });
                    showerror('',html);
                }else if(objkey_exists(js,'original')){
                    js=e.responseJSON.message.original;
                    if(objkey_exists(js,'error')){
                        js=js.error;
                        var newArr=objToSingleArray(js);
                        newArr=newArr.join('<br>');
                        showerror('',newArr,'error');
                    }else if(objkey_exists(js,'message')){
                        js=js.message;
                        console.log(js);
                        showerror('',`<br>${js.message}`);
                    }
                }
            }
        }   
    }
}
function loadapi(){
    if(localStorage.getItem("clientInfo") !== null){
        console.log("client info set before");
        return true;
    }
    los=atob(clientInfo);
    los=JSON.parse(los);
    client_id=atob(los[0]);
    client_secret=atob(los[1]);
    var xhr = new XMLHttpRequest();
    var url = websitelink+"/oauth/token";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            var endtime = new Date();
            endtime.setSeconds(endtime.getSeconds() + json.expires_in);
            endtime=UnixTime(endtime,'to');
            var token_type=json.token_type;
            var access_token=json.access_token;
            localStorage.setItem('clientInfo',JSON.stringify({endtime,token_type,access_token}))
            
            return true;
        }
    };
    var data = JSON.stringify({"grant_type": "client_credentials",'client_id':client_id,'client_secret':client_secret});
    xhr.send(data);
}