import { PrintDiv } from "../printElement.js";
(function(){
    $.ajaxSetup({ cache: true });
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    var URL=api+'employment/getresult';
    const API_URL = URL;
    var clientinfo=JSON.parse(localStorage.getItem('clientInfo'));
function set_ann_and_job() {
    var url,sf;
    if ((typeof $('input[id=annonce]').val() === 'undefined') || (typeof $('input[id=job]').val() === 'undefined')) {
        return 'error';
    }
    url = document.URL;
    sf = url.split('/');
    if (sf[sf.length - 3] !== 'stage') { return 'error'; }
    $('input[id=annonce]').val(sf[sf.length - 2]);
    $('input[id=job]').val(sf[sf.length - 1]);
    return true;
}

function get_data() {
    var annid,jobid,nid,datalist,getjob_link,st;
    var annid = $('input[id=annonce]').val();
    var jobid = $('input[id=job]').val();
    var nid=$('#nid').val();
    var resultarea=$('#demo');
    if(nid.length !== 14 || annid === null || annid === '' || jobid === null || jobid === ''){
        resultarea.html(jstrans['nidisnot14']);
        return;
    }
    datalist={annonceSlug:annid,jobSlug:jobid,nid:nid,page:'search'};
    getjob_link = api + 'employment/getresult';
    jQuery.ajax({
        url: API_URL,
        dataType: 'html',
        contentType:'application/x-www-form-urlencoded',
        type: 'post',
        cache: true,
        data:datalist,
        crossDomain: true,
        converters :{"* text": window.String, "text html": true, "text json": jQuery.parseJSON, "text xml": jQuery.parseXML},
        beforeSend: function() {
            loader_div();
            //$('[data-bs-refresh='+$(SendInput).attr('id')+']').remove();
        },
        complete: function() {
            remove_loader_div();
        },
        async: false,
        success: function(data) {
            if(typeof data == 'string'){
                if(!isJson(data)){console.log(52); return;}
                data=JSON.parse(data);
                if(!objkey_exists(data,'data')){console.log(54); return;}
                data=data.data;
                if(typeof data !== 'object'){console.log(56); return;}
                if(!objkey_exists(data,0)){console.log(57); return;}
                data=data[0];
                $('#demo').html(data);
                $('input[type=hidden][name=_token]').val($('meta[name=csrf-token]').attr('content'))
                return;
            }
            var file= new Blob([data],{type:'application/pdf'});
            var st=data.split(';\r\n');
            var st=st[2].split('\r\n\r\n')
            var iframe= document.createElement('iframe');
            $(iframe).attr('style','top:0; left:0; bottom:0; right:0; width:100%; height:30cm; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;');
            $('#demo').html(iframe);
            iframe.src="data:application/pdf;base64,"+st[1]
        },
        error: function(e, xhr, opt) {
            if(isJson(e.responseText)){
                var jsonerror=JSON.parse(e.responseText);
                if(objkey_exists(jsonerror,'message')){
                    if(objkey_exists(jsonerror['message'],'message')){
                        showerror(jsonerror['message']['number'],jsonerror['message']['message']);
                    }
                }
            }
            remove_loader_div();//faileajax(SendInput,'setAcceptOptions');
            console.log("error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        },
        statusCode: {
            402: function(e) {
                ResponseError(e);
            }
          }
    });
}

function set_result(data,date) {
    if (data.result == 'error' || data.result == 'errorannonce') {
        $('#demo').html('');
        var dsd = '';
        dsd += data.message;
        $('#demo').html(dsd);
        $('#mod_nid').html($('#nid').val());
        return '';
    }
    set_html(data,date)
}
    if (set_ann_and_job() == 'error') { $('form').remove(); }
    var element = document.getElementById("selectbox");
    element.classList.remove("d-none");

    const annid = $('input[id=annonce]').val();
    const jobid = $('input[id=job]').val();
    var inp = document.getElementById("nid");
    inp.addEventListener("keydown", function(e) {
        if (e.keyCode === 13) { //checks whether the pressed key is "Enter"
            get_data();
        }
    });
    $('#search').click(function() {
        get_data();
        return '';
        jQuery.ajax({
            url: getjob_link,

            success: function(data) {
                if (data == 'error') {
                    $('#demo').remove();
                    dsd = 'لم يتقدم هذا الرقم القومى (' + $('#nid').val() + ')  لهذا الاعلان';
                    showerror('بحث عن نتيجة', dsd);
                    $('#mod_nid').html($('#nid').val());
                    return '';
                }
                var $result, $mess;
                var vals = data[0];
                var userinfo;
                var input_name = '';
                var input_nid = '';
                var input_annonce = '';
                var input_job_info = '';
                var input_statue = '';
                var input_message = '';
                if (vals['last_enter']['type'] == 'data') { userinfo = vals['employment_people_new_data'][vals['last_enter']['key']]; }
                if (vals['last_enter']['type'] == 'fst') { userinfo = vals; }
                input_name = userinfo['fname'] + ' ' + userinfo['sname'] + ' ' + userinfo['tname'] + ' ' + userinfo['lname'];
                input_nid = vals['nid'];
                input_annonce += 'اعلان رقم (' + vals['annonce_id']['number'] + ')';
                input_annonce += ' لسنة (' + vals['annonce_id']['year'] + ')';
                input_job_info = 'كود:' + userinfo['Job_id']['code'] + ' - وظيفة: ' + userinfo['Job_id']['name'] + '(' + userinfo['Job_id']['job_name'] + ')';
                lsstages = vals['employment_people_new_stage'][vals['employment_people_new_stage'].length - 1];
                console.log(vals['employment_people_new_stage']);
                input_statue = vals['last_stage']['result'];
                input_message = vals['last_stage']['message'];
                $('#mod_name').html(input_name);
                $('#mod_nid').html(input_nid);
                $('#annonce_name').html(input_annonce);
                $('#job_name').html(input_job_info);
                $('#mod_result').html(input_statue);
                $('#trojan').html(input_message);
                return '';
                console.log(userinfo);



                var input_button = '';
                //console.log(vals);
                var default_stage = vals;
                var stages = vals['employment_people_new_stage'];
                var stages_data = vals['employment_people_new_data'];

                input_nid += vals['nid'];
                volc = get_ls_res(data);
                input_name = volc[0];
                input_job_info = volc[1];
                input_statue = volc[2];
                input_button = volc[3];
                //input_job_info


                $('#nst').html(input_button);
            },
        });
    });
function set_html(data,date) {
    var html, json, id, nid, lastdata, fullname, annonce, job, laststage, result, message, instructions,$lsd,link,qr;
    var json = data[0];
    var template=$('template[id=SearchResultTemplate]');
    var inner=template.html();
    $('#demo').html(inner);
    var id = json['id'];
    var nid = json['NID'];

    var laststage = json['stageList']['Last'];
    if (laststage['Type'] === 'Stage') {
        
        if (laststage['StageId'] === null) {
            instructions = 'شكرا لوقتكم';
        } else {
            $lsd = laststage['StageId'];
            link=websitelink + '/employment_operation/stage/' + json['Annonce_id']['Slug'] + '/' + json['Job_id']['Slug'];
            instructions=`<form method="post" action="`+link+`" id="formInstruction">
                <input type="hidden"  id="job" name="job" value="`+json['Job_id']['slug']+`">
                <input type="hidden"  id="annonce" name="annonce" value="`+json['Annonce_id']['slug']+`">
                <input type="hidden"  id="page" name="page" value="search">
                <input type="hidden" name="nid" value="`+nid+`">
                <input type="hidden" name="lastStage" value="`+$lsd+`">
                <input type="hidden" name="_token" value="`+$('meta[name="csrf-token"]').attr('content')+`">
                <input type="hidden" name="_method" value="POST">
            <a role="link" class="btn" aria-disabled="true" id="forminstructionLink">` + laststage['Text'] + `</a>
            </form>`;
        }
        var result;
        $.each(STATUS,function(k,v){
            if(v['id'] == laststage['result']){
                result=v['Text'];
            }
        });
    if (result === undefined) { result = 'لم يتم اعلان النتيجة'; }
    } else if (laststage['type'] === 'entry') {
        //lsndkey=json['last_enter']['key'];
        
        var lastnewdataid = json['Employment_PeopleNewData']['Stage_id'];
        
        var lastconvertid = json['Stages']['list'][json['Stages']['list'].length - 1]['id'];
        if (lastnewdataid === lastconvertid) {
            laststage['message'] = '';
            result = 'تم ادخال البيانات بنجاح';
        }
        instructions = '';
        
    }else if(laststage['Type'].startsWith('Grievance')){
        laststage['message'] = 'لم يتم اعلان النتيجة';
        result = 'تم ادخال التظلم بنجاح';
        instructions = '';
    } else if (laststage['Type'] === 'apply') {
        result = laststage['Result'];
        if (result === undefined) { result = 'لم يتم اعلان النتيجة'; }
        if (laststage['message'] == null) {
            laststage['message'] = '';
        }
        instructions = '';
    }
    $('#SearchResult_result').html(result);
    if (laststage['message'] == '' || laststage['message'] == null) {
        $('#SearchResult_message').parent().hide();
    }else{
        $('#SearchResult_message').html(laststage['message'])
    }
    if (instructions == '' || instructions == null) {
        $('#SearchResult_Instructions').parent().hide();
    }else{
        $('#SearchResult_Instructions').html(instructions)
    }
    $('#forminstructionLink').on('click',function(){$('#formInstruction').submit();});
    $('#servertime').append(date);
    qr=[id,nid,laststage.key,laststage.result,date];
    $('#QrCode').qrcode({
        mode: 0,
        render: 'canvas',
        minVersion: 1,
        maxVersion: 40,
        ecLevel: 'L',
        left: 0,
        top: 0,
        size: 180,
        fill: '#000',
        background: '#fff',
        radius: 0,
        quiet: 0,
        mSize: 0.1,
        mPosX: 0.5,
        mPosY: 0.5,
        text:qr.join('-'),
    });
}
    $('#printBTN').click(function(){       
        PrintDiv('demo',jstrans['result'],null,$('#printBTN').attr('stylefile'));
    });
})(jQuery)
