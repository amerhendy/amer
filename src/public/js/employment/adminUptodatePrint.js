(function(){
$.ajaxSetup({ cache: true });
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
var URL=api+"employmentReports/PrintForm"
const API_URL = URL;
    const settings = {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        redirect: "manual",
        body: JSON.stringify(Data),
        xhrFields: {				// Uses the jquery-ajax-native plugin for blobs.
            responseType: "blob"
        },
    };
    jQuery.ajax({
        url: API_URL,
        dataType: 'html',
        contentType:'application/x-www-form-urlencoded',
        type: 'post',
        cache: true,
        data:Data,
        crossDomain: true,
        converters :{"* text": window.String, "text html": true, "text json": jQuery.parseJSON, "text xml": jQuery.parseXML},
        beforeSend: function() {
            loader_div();
            //$('[data-bs-refresh='+$(SendInput).attr('id')+']').remove();
        },
        complete: function() {
            remove_loader_div();
        },
        success: function(data) {
            var file= new Blob([data],{type:'application/pdf'});
            var st=data.split(';\r\n');
            var st=st[2].split('\r\n\r\n')
            iframe= document.createElement('iframe');
            $(iframe).attr('style','position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;');
            $('body').html(iframe);
            iframe.src="data:application/pdf;base64,"+st[1]
        },
        error: function(e, xhr, opt) {
            if(isJson(e.responseText)){
                jsonerror=JSON.parse(e.responseText);
                if(objkey_exists(jsonerror,'message')){
                    if(objkey_exists(jsonerror['message'],'message')){
                        showerror(jsonerror['message']['number'],jsonerror['message']['message']);
                    }
                }
            }
            remove_loader_div();//faileajax(SendInput,'setAcceptOptions');
            console.log("error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        }
    }); 
})(jQuery);