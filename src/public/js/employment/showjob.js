(function() {
    id = $('#showjobs');
    annid = id.attr('annonceid');
    jobid = id.attr('jobid');
    getjob_link = websitelink + '/api/employment/getjob/' + annid + '/' + jobid;
    jQuery.ajax({
        url: getjob_link,
        dataType: 'json',
        type: 'post',
        success: function(data) {
            if(data.recordsTotal !== 1){
                return;
            }
            window.set_job(id, data.data);
        },
        error: function(e, xhr, opt) {
            showerror(jstrans['error'], "requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });
    set_job=function (id, data) {
        res = data;
        annonce = res['Employment_StartAnnonces'];
        setspan('.sjaayy', annonce['Year']);
        setspan('.sjaan', annonce['Number']);
        var lenplace  = annonce['Governorates'].length;
        if(lenplace !== 0){
            setspan('.sjaag', annonce['Governorates'].join(' - '));   
        }
        setspan('.sjaad', annonce['Description']);
        /////////////////////////////////////////
        var Mosama_JobTitles=res['Mosama_JobNames']['Text']+'('+ res['Mosama_JobTitles']+')';
            setspan('.Mosama_JobTitles',res['Mosama_JobTitles']);
            setspan('.Code',res['code']);
        if (res['Description'] === null) {
            $('.full_job_description_class').remove();
        } else {
            setspan('.Description', res['Description']);
        }
        if (res['Group'] !== null) {
            setspan('.Mosama_Groups', res['Mosama_Groups']);
        }
        var $experince=new Array();
        $.each(res['Mosama_JobNames']['Mosama_Experiences'],function(k,v){
            if(v[1] === 0){$experince.push("لا يتطلب خبرة");}
            else{
                if(v[0]=== '1'){
                    $experince.push('خبرة فى مجال العمل لمدة ('+v[1]+') سنوات');
                }else{
                    $experince.push('مدة بينية فى وظيفة بنفس المسمى الوظيفى فمدة  ('+v[1]+') سنوات');
                }
            }
        })
        setspan('.Mosama_Experiences',$experince.join(' او '));
        if(res['Count'] == null){
            $('.full_job_count').remove();
        }else{
            setspan('.Count',res['Count']);
        }
        setspan('.Employment_Instructions','<ul><li>'+res['Employment_Instructions'].join('</li><li>')+'</li></ul>');
        setspan('.Employment_Ama','<ul><li>'+res['Employment_Ama'].join('</li><li>')+'</li></ul>');
        setspan('.Employment_Army','<ul><li>'+res['Employment_Army'].join('</li><li>')+'</li></ul>');
        setspan('.Mosama_Educations','<ul><li>'+res['Mosama_Educations'].join('</li><li>')+'</li></ul>');
        if(res['Mosama_JobNames']['Mosama_Skills'] !== null){
            setspan('.Mosama_Skills', '<ul><li>'+res['Mosama_JobNames']['Mosama_Skills'].join('</li><li>')+'</li></ul>');
        }
        setspan('.Employment_IncludedFiles', '<ul><li>'+res['Employment_IncludedFiles'].join('</li><li>')+'</li></ul>');
        setspan('.Employment_health', '<ul><li>'+res['Employment_Health'].join('</li><li>')+'</li></ul>');
        if(res['Driver'] == '1'){
            $('.all-Employment_Drivers').remove()
        }else{
            setspan('.Employment_Drivers', res['Employment_Drivers'].join(' أو '));
        }
        setspan('.Mosama_Goals','<ul><li>'+res['Mosama_JobNames']['Mosama_Goals'].join('</li><li>')+'</li></ul>');
        setspan('.Employment_Qualifications', '<ul><li>'+res['Employment_Qualifications'].join('</li><li>')+'</li></ul>');
        setspan('.Cities', '<ul><li>'+res['Cities'].join('</li><li>')+'</li></ul>');
        setspan('.Mosama_Competencies', '<ul><li>'+res['Mosama_JobNames']['Mosama_Competencies'].join('</li><li>')+'</li></ul>');
        setspan('.Employment_MaritalStatus', '<ul><li>'+res['Employment_MaritalStatus'].join('</li><li>')+'</li></ul>');
        if(res['Mosama_JobNames']['Mosama_Tasks'] !== null){
            setspan('.Mosama_Tasks', '<ul><li>'+res['Mosama_JobNames']['Mosama_Tasks'].join('</li><li>')+'</li></ul>');
        }
        res['AgeIn']=numberDateToArabic(res['AgeIn']['year'],res['AgeIn']['month'],res['AgeIn']['day']);
        setspan('.sjanm', res['Age']);
        setspan('.sjanmat', res['AgeIn']);
        window.createfooter();
    
    }
    createfooter=function(){
            //create footer
            document.getElementById('nidannoncestage').value=annonce['Employment_Stages'][2];
        footersection=$('section[id=showJob-footer]');
        $(footersection).append($(`<a class="btn btn-primary btn-lg" href="#" role="button" onclick="print();" type="button">طباعة</a>`));
        askForNid=parseInt(annonce['Employment_Stages'][1]);
        if(askForNid == 0){
            $(footersection).append($(`<span class="btn btn-primary btn-lg " id="app_pro" role="button"  data-bs-toggle="modal" data-bs-target="#exampleModal"></span>`));
            setspan('#app_pro', annonce['Employment_Stages'][0]);
            $('#app_pro').attr('front', annonce['Employment_Stages'][1]);
            $('#app_pro').attr('code', annonce['Employment_Stages'][2]);
            document.getElementById('app_pro').addEventListener('click',function(){window.stage()})
        }else if(askForNid === 1){
            form=$(footersection).closest('form');
            $(form).append(`<input type="hidden" name="_token" value="`+$('meta[name="csrf-token"]').attr('content')+`">`);
            $(form).attr('method','post');
            button=$('<button type="button" id="gototstatic" class="btn btn-primary btn-lg"><i class="fa fa-eye" aria-hidden="true"></i>'+annonce['Employment_Stages'][0]+'</button>');
            $(footersection).append($(button));
            document.getElementById('gototstatic').addEventListener('click',function(){window.gotostaticpages(this)})
        }
    }
    gotostaticpages=function(e){
        form=$(e).closest('form');
        var job_val = $('#showjobs').attr('jobid');
        var annonce_val = $('#showjobs').attr('annonceid');
        var btnlink= websitelink + "/employment_operation/stage/" + annonce_val + "/" + job_val;
        $(form).attr('action',btnlink);
        $(form).attr('method','post');
        $(form).submit();
    }
    stage=function(){
            document.getElementById('nid').addEventListener('blur',function(){trim(this)})
            document.getElementById('nid').addEventListener('input',function(){window.nid_on_input(this)})
    }
    nid_on_input=function (e) {
        var nid_input = $(e);
        var form=$(e).closest('form');
        var nid_val = nid_input.val();
        var nid_lenght = nid_val.length;
        var btn = $('#savechanges');    
        var job_val = $('#showjobs').attr('jobid');
        var annonce_val = $('#showjobs').attr('annonceid');
        var btnlink= websitelink + "/employment_operation/stage/" + annonce_val + "/" + job_val;
        var realnid = $('#nidreal');
        var apilink = websitelink + '/api/';
        var trans = jstrans;
        if (nid_lenght !== 14) {
            realnid.html(nid_lenght + '-14');
            btn.hide()
            $(form).attr('action','');
        } else {
            res = 0;
            res += nid_val[0] * 2;
            res += nid_val[1] * 7;
            res += nid_val[2] * 6;
            res += nid_val[3] * 5;
            res += nid_val[4] * 4;
            res += nid_val[5] * 3;
            res += nid_val[6] * 2;
            res += nid_val[7] * 7;
            res += nid_val[8] * 6;
            res += nid_val[9] * 5;
            res += nid_val[10] * 4;
            res += nid_val[11] * 3;
            res += nid_val[12] * 2;
            res = res % 11;
            res = 11 - res;
            if (res > 9) res = res % 10;
            if (res == nid_val[13]) {
                var job_val = $('#showjobs').attr('jobid');
                var annonce_val = $('#showjobs').attr('annonceid');
                jQuery.ajax({
                    url: apilink + 'employment/checknid/' + annonce_val + "/" + job_val,
                    dataType: 'json',
                    data:form.serializeArray(),
                    type: 'post',
                    success: function(data) {
                        if (data['result'] === 'not14') {
                            realnid.html(data['message']);
                            btn.hide();$(form).attr('action','');
                        } else if (data['result'] === 'errorannonce') {
                            realnid.html(data['message']);
                            btn.hide();$(form).attr('action','');
                        } else if (data['result'] === 'errorjob') {
                            realnid.html(data['message']);
                            btn.hide();$(form).attr('action','');
                        } else if (data['result'] === 'isset') {
                            realnid.html(data['message']);
                            btn.hide();$(form).attr('action','');
                        } else if (data['result'] === 'success') {
                            realnid.html(data['message']);
                            btn.html($('#app_pro').html());
                            btn.show();$(form).attr('action',btnlink);
                        } else {
                            realnid.html('من فضلك راسلنا على البريد الاليكترونى<br>اضف رابط الصفحة واكتب الخطوات التى قمت بها');
                            btn.hide();$(form).attr('action','');
                        }
                    },
                });
            } else {
                realnid.html(trans['nid_phisical_error']);
                btn.hide()
            }
        }         
    }
    insertlinkform=function(){}
})(jQuery)
