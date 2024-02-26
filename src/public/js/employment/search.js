import { PrintDiv } from "../printElement.js";
(function(){
    

$.ajaxSetup({ cache: false });
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
    getjob_link = websitelink + '/api/employment/getresult/' + annid;
    jQuery.ajax({
        url: getjob_link,
        dataType: 'json',
        data:datalist,
        type: 'post',
        async: false,
        crossDomain: true,
        success: function(data,e, xhr, opt) {
            st= xhr.getResponseHeader("Date");
            var date = new Date(st);
            set_result(data.data,date);
        },
        error: function(e, xhr, opt) {
            showerror("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
            console.log(xhr);
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
    var json = data;
    var template=$('template[id=SearchResultTemplate]');
    var inner=template.html();
    $('#demo').html(inner);

    var id = json['id'];
    var nid = json['NID'];
    $('#SearchResult_id').html(id);
    $('#SearchResult_nid').html(nid);
    var newdata = json['Employment_PeopleNewData'];
    if (json['Stages']['final']['type'] === 'entry') {
        json['fname'] = newdata['fname'];
        json['sname'] = newdata['sname'];
        json['tname'] = newdata['tname'];
        json['lname'] = newdata['lname'];
        json['Job_id'] = newdata['Job_id'];
    }
    fullname = json['Fname'] + ' ' + json['Sname'] + ' ' + json['Tname'] + ' ' + json['Lname'];
    $('#SearchResult_fullname').html(fullname);
    annonce = jstrans['homepage_annonce_number'] + ' (' + json['Annonce_id']['number'] + ') ' + jstrans['homepage_annonce_foryear'] + ' (' + json['Annonce_id']['year'] + ')';
    job = jstrans['code'] + ':(' + json['Job_id']['code'] + ') - ' + json['Job_id']['job_name'];
    $('#SearchResult_annonce').html(annonce);
    $('#SearchResult_job').html(job);
    laststage = json['Stages']['final'];
    if (laststage['type'] === 'stage') {
        if (laststage['stage'] === null) {
            instructions = 'شكرا لوقتكم';
        } else {
            $lsd = laststage['stage']['id'];
            link=websitelink + '/employment_operation/stage/' + json['Annonce_id']['slug'] + '/' + json['Job_id']['slug'];
            instructions=`<form method="post" action="`+link+`" id="formInstruction">
                <input type="hidden"  id="job" name="job" value="`+json['Job_id']['slug']+`">
                <input type="hidden"  id="annonce" name="annonce" value="`+json['Annonce_id']['slug']+`">
                <input type="hidden"  id="page" name="page" value="search">
                <input type="hidden" name="nid" value="`+nid+`">
                <input type="hidden" name="lastStage" value="`+$lsd+`">
                <input type="hidden" name="_token" value="`+$('meta[name="csrf-token"]').attr('content')+`">
                <input type="hidden" name="_method" value="POST">
            <a role="link" class="btn" aria-disabled="true" id="forminstructionLink">` + laststage['stage']['Text'] + `</a>
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
    } else if (laststage['type'] === 'tazalom') {
        laststage['message'] = 'لم يتم اعلان النتيجة';
        result = 'تم ادخال التظلم بنجاح';
        instructions = '';
    } else if (laststage['type'] === 'fst') {
        result = jstrans[laststage['result']];
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
