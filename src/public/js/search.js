set_status_options();

function set_ann_and_job() {
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
    annid = $('input[id=annonce]').val();
    jobid = $('input[id=job]').val();
    if (annid === null) { alert('من فضلك ادخل رقم قومى سليم'); return ''; }
    if (annid === '') { alert('من فضلك ادخل رقم قومى سليم'); return ''; }
    if (jobid === null) { alert('من فضلك ادخل رقم قومى سليم'); return ''; }
    if (jobid === '') { alert('من فضلك ادخل رقم قومى سليم'); return ''; }
    getjob_link = websitelink + '/api/employment/getresult/' + annid + '/' + $('#nid').val();
    jQuery.ajax({
        url: getjob_link,
        dataType: 'json',
        type: 'get',
        async: false,
        crossDomain: true,
        success: function(data) {
            set_result(data);
        },
        error: function(e, xhr, opt) {
            showerror("Error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
            console.log(xhr);
        }
    });
}

function set_result(data) {
    if (data == 'error') {
        $('#demo').html('');
        var dsd = '';
        dsd += $('#nid').val();
        dsd += '<br>';
        dsd += trans['this_nid_not_in_annonce'];
        dsd += '<br>';
        dsd += trans['please_enter_right_nid'];
        showerror(trans['lookforresult'], dsd);
        $('#mod_nid').html($('#nid').val());
        return '';
    }
    set_html(data)
    var servertime = Base64.encode(st);
    var qrcode = new QRCode("servertime", {
        text: data['id'] + '&&' + servertime,
        colorDark: "#0a384f",
        colorLight: "#ffffff",
    });
}
(function() {
    if (set_ann_and_job() == 'error') { $('form').remove(); }
    var element = document.getElementById("selectbox");
    element.classList.remove("d-none");

    annid = $('input[id=annonce]').val();
    jobid = $('input[id=job]').val();



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
                input_job_info = 'كود:' + userinfo['job_id']['code'] + ' - وظيفة: ' + userinfo['job_id']['name'] + '(' + userinfo['job_id']['job_name'] + ')';
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
})(jQuery)



function set_html(data) {
    console.log(data);
    var html, json, id, nid, lastdata, fullname, annonce, job, laststage, result, message, instructions;
    json = data;
    id = json['id'];
    nid = json['nid'];
    var newdata = json['employment_people_new_data'];
    if (json['stages']['final']['type'] === 'entry') {
        json['fname'] = newdata['fname'];
        json['sname'] = newdata['sname'];
        json['tname'] = newdata['tname'];
        json['lname'] = newdata['lname'];
        json['job_id'] = newdata['job_id'];
    }
    fullname = json['fname'] + ' ' + json['sname'] + ' ' + json['tname'] + ' ' + json['lname'];
    annonce = trans['homepage_annonce_number'] + ' (' + json['annonce_id']['number'] + ') ' + trans['homepage_annonce_foryear'] + ' (' + json['annonce_id']['year'] + ')';
    job = trans['code'] + ':(' + json['job_id']['code'] + ') - ' + json['job_id']['job_name'];
    laststage = json['stages']['final'];
    if (laststage['type'] === 'stage') {
        if (laststage['stage'] === null) {
            instructions = 'شكرا لوقتكم';
        } else {
            $lsd = laststage['stage']['id'];
            instructions = '<a href="';
            instructions += websitelink + '/employment_operation/stage/' + json['annonce_id']['slug'] + '/' + json['job_id']['slug'] + '/' + nid + '/' + $lsd;
            instructions += '">' + laststage['stage']['name'] + '</a>';
        }
        result = trans[laststage['result']];
        if (result === undefined) { result = 'لم يتم اعلان النتيجة'; }
    } else if (laststage['type'] === 'entry') {
        //lsndkey=json['last_enter']['key'];
        lastnewdataid = json['employment_people_new_data']['stage'];
        lastconvertid = json['employment_people_new_stage'][json['employment_people_new_stage'].length - 1]['id'];
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
        result = trans[laststage['result']];
        if (result === undefined) { result = 'لم يتم اعلان النتيجة'; }
        if (laststage['message'] == null) {
            laststage['message'] = '';
        }
        instructions = '';
    }
    html = '';
    html += '<div class="" role="document">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header">';
    html += '<h5 class="modal-title" id="resultLongTitle">';
    html += trans['searchpage_searchresult'];
    html += '</h5>';
    html += '</div>';
    html += '<div class="modal-body">';
    html += '<div class="container-fluid">';
    html += '<div class="row">';
    html += '<div class="col-md-6 text-right">';
    html += '<div class="row">';
    html += '<div class="col-md-3 text-right">' + trans['uid'] + '</div>';
    html += '<div class="col-md text-right">' + id + '</div>';
    html += '</div>';
    html += '<div class="row">';
    html += '<div class="col-md-3 text-right">' + trans['nid'] + '</div>';
    html += '<div class="col-md text-right">' + nid + '</div>';
    html += '</div>';
    html += '<div class="row">';
    html += '<div class="col-md-3 text-right">';
    html += trans['fullname'];
    html += '</div>';
    html += '<div class="col-md  text-right">';
    html += fullname;
    html += '</div>';
    html += '</div>';
    ////
    html += '<div class="row">';
    html += '<div class="col-md-2 text-right">';
    html += trans['annonce_name'];
    html += '</div>';
    html += '<div class="col-md  text-right">';
    html += annonce;
    html += '</div>';
    html += '</div>';
    html += '<div class="row">';
    html += '<div class="col-md-2 text-right">';
    html += trans['job_name'];
    html += '</div>';
    html += '<div class="col-md  text-right">';
    html += job;
    html += '</div>';
    html += '</div>';
    html += '<div class="row">';
    html += '<div class="col-md-2 text-right">';
    html += trans['result'];
    html += '</div>';
    html += '<div class="col-md  text-right">';
    html += result;
    html += '</div>';
    html += '</div>';
    /////
    html += '</div>';
    html += '<div class="col-md-6 text-right">';
    html += '<div id="servertime"></div>';
    html += '</div>';
    html += '</div>';



    if (laststage['message'] !== '') {
        html += '<div class="row">';
        html += '<div class="col-md-2 text-right">';
        html += trans['message'];
        html += '</div>';
        html += '<div class="col-md  text-right border rounded">';
        html += laststage['message'];
        html += '</div>';
        html += '</div>';
    }
    if (instructions !== '') {
        html += '<div class="row">';
        html += '<div class="col-md-2 text-right">';
        html += trans['instructions'];
        html += '</div>';
        html += '<div class="col-md  text-right">';
        html += instructions;
        html += '</div>';
        html += '</div>';
    }
    html += '</div>';
    html += '</div>';
    html += '';


    html += '</div>';
    html += '</div>';
    $('#demo').html(html);


}

function set_status_options() {
    var URL = websitelink + "/api/status";
    jQuery.ajax({
        url: URL,
        dataType: 'json',
        type: 'get',
        beforeSend: function() {},
        complete: function() {

        },

        success: function(data) {
            $.each(data, function(key, value) {
                trans[value['id']] = value['name'];
            });


        },
        error: function(e, xhr, opt) {
            //alert("error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        }
    });

}