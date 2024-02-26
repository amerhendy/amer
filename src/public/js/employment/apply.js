(function(){
    $.ajaxSetup({ cache: false });
window.Amer['apply']=[];
window.Amer['apply']['accept_driver']=1;
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
    $('input[name="EducationYear"]').datepicker({
        format: "yyyy",
        language: "ar",
        assumeNearbyYear:true,
        defaultViewDate:'year',
        toggleActive: true,
        viewMode: "years", minViewMode: "years",
        multidate: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true
    });
    //set annonce and job ids
    setAnnonceJobInfo=function(){
        var pathname = window.location.pathname;
        var patharray = pathname.split('/');
        var stage_index = patharray.indexOf('stage');
        annonce_slug = patharray[stage_index + 1];
        job_slug = patharray[stage_index + 2];
        document.getElementById('annonce_id').value = annonce_slug;
        document.getElementById('job_id').value = job_slug;
        $('#hiddens').attr('data', annonce_slug);
        $('#hiddens').attr('jb', annonce_slug);
    }
    ///////////// get all validation messgages
    showallerrors=function (alop) {
        var allop = Array();
        $error = '';
        var dod = '<ul class="text-right">';
        var type = '';
        if (alop.hasOwnProperty('msg')) {
            type = 'message';
            if (alop['msg'] === 0) { v = jstrans['data_entered']; }
            if (alop['msg'] === 1) { v = jstrans['data_not_entered']; }
            dod += '<li>' + v + '</li>';
        } else {
            $.each(alop, function(key, value) {
                $('*[name=' + key + ']').parent().addClass('danger-color');
                console.log($('*[name=' + key + ']'));
            });
            $.each(alop, function(key, value) {
                type = 'input';
                console.log(value);
                value.forEach(function(v) {
                    dod += '<li>' + v + '</li>';
                });
            });
        }
        dod += '</ul>';
        return [type, dod];
        $.each(alop, function(key, value) {
            if (key === 'msg') {
                type = 'message';
                value.forEach(function(v) {
                    if (v === 0) {
                        v = jstrans['data_entered'];
                    }
                    if (v === 1) {
                        v = jstrans['data_not_entered'];
                    }
                    dod += '<li>' + v + '</li>';
                });
            } else {
                type = 'input';
                $('*[name=' + key + ']').parent().addClass('danger-color');
                value.forEach(function(v) {
                    dod += '<li>' + v + '</li>';
                });
            }
        });
        dod += '</ul>';
        return [type, dod];
    }
    check_reqerrors=function () {
        var element = $('.errors');
        var alop = element.attr('alop');
        if (alop === '') { return ''; }
        $alop = JSON.parse(alop);
        if ($alop.length === 0) { return ''; }
        $shr = showallerrors($alop);
        $ti = '';
        if ($shr[0] === 'message') { $ti = ''; }
        if ($shr[0] === 'input') { $ti = jstrans['pleasefillinputs']; }
        $shr[0] = '';
        
        showerror($ti, $shr);
    }
    checkinput=function () {
        var arrayOfInputNames, elmts, L;
        elmts = document.getElementsByTagName("input");
        arrayOfInputNames = [];
        L = elmts.length;
        for (var i = 0; i < L; i++) {
            arrayOfInputNames.push(elmts[i].name);
        }
        arrayOfInputNames.forEach(function(a, b) {
            var attr = $('input').attr('name');
            if (typeof attr !== 'undefined' && attr !== false) {
                var input_name = $('input[name=' + attr + ']');
                input_name.on('focusout', function() {
                    var minlen = input_name.attr('minlen');
                    check_focus_empty(input_name, minlen);
                });
            }
        });
    }
    check_focus_empty=function (name, sd) {
        if (name.val().length < sd) {
            name.addClass('border border-danger');
        }
        if (name.val().length > sd) {
            name.removeClass('border border-danger');
        }
    }
    loadselects=function(){
        $.each($('[data-init-function]'),function(k,v){
            var element = $(v);
            var functionName = element.data('init-function');
            if (typeof window[functionName] === "function") {
                window[functionName](element);
            }
        });
    }
    /// set apply datetime////
    set_current_date_time=function (e) {
        var dd = new Date().toISOString().slice(0, 19).replace('T', ' ');
        id = e.attr('id');
        document.getElementById(id).value = dd;
        //view_noty('success', jstrans['times_added']);
    }
    setkhebraType=function(){
        type=$('#Khebra_type').val();
        var input=$('input[name="Khebra"]')
        if(type == '2'){
            input.val('0');
            input.attr('readonly','');
        }else{
            input.removeAttr('readonly');
        }
    }
    loadselects();
    
    setAnnonceJobInfo()
    if($('#NID').val()!==''){
        var NIDval=$('#NID').val();
        var NIDValArray=NIDval.split('');
        for(i=0;i<NIDValArray.length;i++){
            $('input[class=ap-otp-input][name=NID_'+[i]+']').val(parseInt(NIDValArray[i]));
        }
    }
    check_reqerrors();
    checkinput();
    setkhebraType();
$('.progress').hide();
$('form').on('submit', function() {
    $('.progress').show();
    return '';
    move();
});

SETQRCODE=function(information) {
    $('main').qrcode({
        mode: 0,
        render: 'canvas',
        minVersion: 1,
        maxVersion: 40,
        ecLevel: 'L',
        left: 0,
        top: 0,
        size: 100,
        fill: '#000',
        background: null,
        radius: 0,
        quiet: 0,
        mSize: 0.1,
        mPosX: 0.5,
        mPosY: 0.5,
        text:'amer',
    
    });
}
var i = 0;
move=function () {
    if (i == 0) {
        i = 1;
        var elem = document.getElementById('progress-bar');
        var width = 1;
        var id = setInterval(frame, 10);

        function frame() {
            if (width >= 100) {
                clearInterval(id);
                i = 0;
            } else {
                width++;
                elem.style.width = width + "%";
            }
        }
    }
}
////////////////////////////////////// on load page /////////////////////////////////
////////////////////////////////// load default page ////////////////////////////////
//// set job and annonce informations ///////////
load_ann_job_info=function (e) {
    $annonce_slug = $('#annonce_id').val();
    $job_slug = $('#job_id').val();
    joblink = websitelink + '/api/employment/getjob/' + $annonce_slug + '/' + $job_slug;
    jQuery.ajax({
        url: joblink,
        method: 'post',
        cache: false,
        dataType: 'json',
        beforeSend:function(){
            $('[data-bs-refresh='+$(e).attr('id')+']').remove();
        },
    }).done(function(data) {
        set_ann_job_info(data,e);
        if($('#NID').val()!==''){
            NID_on_blur(jstrans, 'NID', 'job_id', 'annonce_id', 'NIDreal')
        }
    }).fail(function(jqXHR, textStatus) {
        par=$(e);
        ref=$('<i class="fa fa-refresh text-success" aria-hidden="true" data-bs-refresh="'+$(e).attr('id')+'"></i>')
        $(par).append($(ref))
        $(ref).on('click',function(){
            functionName='load_ann_job_info';
            window[functionName](e)
        });
    });
}
//// set job and annonce informations ///////////
set_ann_job_info=function (data,element) {

    var json = data.data;
    if(data.recordsTotal !== 1){$(element).hide();}
    var $job=json;
    if ($('input[name=actiontype]').val() === 'complete') {
        if (json.Driver === 1) {
            $('#full_Employment_Drivers').hide();
            window.Amer.apply.accept_driver = 1;
        } else {
            $('#full_Employment_Drivers').show();
            window.Amer.apply.accept_driver = 0;
        }
    }else if ($('input[name=actiontype]').val() === 'apply') {
        if (json.Driver == 1) {
            $('#full_Employment_Drivers').hide();
            window.Amer.apply.accept_driver = 1;
        }else{
            window.Amer.apply.accept_driver = 0;
        }
    }
    $annonce = json['Employment_StartAnnonces'];
    setspan('.info_title_functional_annonce_number', $annonce['Number']);
    setspan('.info_title_functional_annonce_number_foryear', $annonce['Year']);
    setspan('.info_title_functional_annonce_desc', $annonce['Description']);
    setspan('.info_title_functional_annonce_place', $annonce['Governorates'].join(' - '));
    setspan('.info_title_name', $job['Mosama_JobTitles']);
    setspan('.info_title_jobname', $job['Mosama_JobNames']['Text']);
    if($job['Description'] == null || $job['Description'] == 'null' || $job['Description'] == ''){
        $('.mployment_JobsDescription').hide();
    }else{
        $('.mployment_JobsDescription').show();
        setspan('.info_job_description', $job['Description']);
    }
    setspan('.info_ama', $job['Employment_Ama'].join(' - '));
    if (json.Driver == 0) {
        $('#full_Employment_Drivers').show();
        $('.full_Employment_Drivers').show();
        setspan('.info_driver_degree', $job['Employment_Drivers'].join(' - '));
    } else {
        $('.full_Employment_Drivers').hide();
        $('#full_Employment_Drivers').hide();
    }
    setspan('.info_mir', $job['Employment_MaritalStatus'].join(' - '));
    if ($job['Count'] === 0 || $job['Count'] === '0' || $job['Count'] === null || $job['Count'] === 'null' || $job['Count'] === 'Null' || $job['Count'] === '') {
        $('.mployment_JobsCount').hide();
    } else {
        $('.mployment_JobsCount').show();
        setspan('.info_Count', $job['Count']);
    }
    var experinces=setKebra(json['Mosama_JobNames']['Mosama_Experiences']);
    if(experinces.length == 0){
        $('.full_Mosama_Experiences').hide();
    }else{
        $('.full_Mosama_Experiences').show();
        setspan('.info_job_khebrayears', experinces.join(' او '));
    }
        setspan('.info_title_functional_class', $job['Mosama_Groups']);
        setspan('.info_health', $job['Employment_StartAnnonces']['Governorates'].join(' - '));
        setspan('.info_idcity', $job['Cities'].join(' - '));
        setspan('.info_arm', $job['Employment_Ama'].join(' - '));
        setspan('.included_files', $job['Employment_IncludedFiles'].join(' - '));
        var ul=$('<ul class="list-unstyled row border-bottom"></ul>')
        $.each($job['Employment_IncludedFiles'],function(k,v){
            $(ul).append($('<li class=" list-item col-4 border-top">'+v+'</li>'))
        });
        setspan('.included_files_apply',$(ul));
        setspan('.info_instructions', $job['Employment_Instructions'].join(' - '));
        var ol=$('<ul type="1"></ul>')
        $.each($job['Employment_Instructions'],function(k,v){
            $(ol).append($('<li class="">'+v+'</li>'))
        });
        setspan('.Employment_Instructions',$(ol));

        setspan('.info_job_code', $job['code']);
        setspan('.info_edu', $job['Mosama_Educations'].join(' - '));
        setspan('.info_Health_id', $job['Employment_Health'].join(' - '));
        setspan('.info_title_functional_annonce_qual', $annonce['Employment_Qualifications'].join('<br>'));
        setspan('.info_title_functional_job_qual', $job['Employment_Qualifications'].join('<br>'));
        setspan('.job_info_age', $job['Age']);
        setspan('.job_info_age_date', numberDateToArabic($job['AgeIn']['year'],$job['AgeIn']['month'],$job['AgeIn']['day']));
        
        var date = new Date($job['AgeIn']['year'],$job['AgeIn']['month'],$job['AgeIn']['day']);
// create a DateTimeFormat object with specific options
var dateFormat = new Intl.DateTimeFormat("en-US", {
	timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone
});
const formatted = dateFormat.format(date);
setspan('.wantedagein',formatted)
}

setKebra=function(arr){
    if(arr.length == 0){return new Array();}
    nww=new Array();
    $.each(arr,function(k,v){
        if(v[1] == 0){
            nww.push('يتطلب معرفة')
        }else{
            if(v[0] == 1){
                v[0]=(jstrans['Mosama_Experiences_enum_1'])
            }else{
                v[0]=(jstrans['Mosama_Experiences_enum_0'])
            }
            nww.push(replacestr(jstrans['Mosama_Experiences_enum_translate'],v))

        }
    });
    return nww;
}
//load driver options
set_driver=function (element) {
    id = element.attr('id');
    li = element.attr('option');
    dd = element.attr('dd');
    set_opt_group_optioss(element, id, li, dd,MainData['Employment_Drivers']);
}
//load arm options
set_arm=function (element) {
    id = element.attr('id');
    li = element.attr('option');
    dd = element.attr('dd');
    set_opt_group_optioss(element, id, li, dd,MainData['Employment_Army']);
}
//load ama options
set_ama=function (element) {
    id = element.attr('id');
    li = element.attr('option');
    dd = element.attr('dd');
    set_opt_group_optioss(element, id, li, dd,MainData['Employment_Ama']);
}
//load education options
set_edu=function (element) {
    id = element.attr('id');
    li = element.attr('option');
    dd = element.attr('dd');
    set_opt_group_optioss(element, id, li, dd,MainData['Mosama_Educations']);
}
//load health options
sethealth=function (element) {
    id = element.attr('id');
    li = element.attr('option');
    dd = element.attr('dd');
    set_opt_group_optioss(element, id, li, dd,MainData['Employment_Health']);
}
//load mir options
set_mir=function (element) {
    id = element.attr('id');
    li = element.attr('option');
    dd = element.attr('dd');
    set_opt_group_optioss(element, id, li, dd,MainData['Employment_MaritalStatus']);
}
//load live gov options
load_default_LiveGov=function (element) {
    var gov_id = element.attr('id');
    var city_id = element.attr('next');
    var old = element.attr('old');
    var $ID = element.attr('id');
    var values = element.attr('vl');
    var show = element.attr('sh');
    json = MainData['places'];
        $.each(json, function(key, value) {
            var group = $('<option value="' + value[values] + '" />').html(value[show]);
            group.appendTo(element);
            if (old !== '') {
                $('#' + gov_id).val(old);
                $('#' + city_id).select2();
                $('#' + city_id).show();
                load_default_City(jstrans, gov_id, city_id);
                $('#' + gov_id).trigger('change');
                
            }
        });
        
        $('#' + $ID).select2({
            tags: "true",
            placeholder: "Select an option",
            allowClear: true,
            width: '100%',
            createTag: function (params) {
              var term = $.trim(params.term);
          
              if (term === '') {
                return null;
              }
              
              // check whether the term matches an id
              var search = $.grep(options, function( n, i ) {
                return ( n.id === term || n.text === term); // check against id and text
              });
              
              // if a match is found replace the term with the options' text
              if (search.length) 
                term = search[0].text;
              else
                return null; // didn't match id or text value so don't add it to selection
              
              return {
               id: term,
               text: term,
               value: true // add additional parameters
              }
            }
          });
          
          $('#' + $ID).on('select2:select', function (evt) {
            //console.log(evt);
            //return false;
          });
}
//view cities
load_default_City=function (jstrans, govid, cityid) {
    var gov_element = $('#' + govid);
    var selected_gov = gov_element.find(':selected').val();
    var old = $('#' + cityid).attr('old');
    var element = $('#' + cityid);
    var fuapilink = websitelink + '/api/employment/cities_by_gov/' + selected_gov;
    var values = element.attr('vl');
    var show = element.attr('sh');
    element.empty();
    var emptyoption = $('<option/>').html('');
    emptyoption.appendTo(element);
    $.each(MainData['places'],function(k,v){
        if(v['id'] == selected_gov){
            json = v['cities'];
            $.each(json, function(key, value) {
                if(old == value[values]){
                    var selected='selected';
                }else{
                    var selected='';
                }
                var group = $('<option '+selected+' value="' + value[values] + '" />').html(value[show]);
                group.appendTo(element);
            });
            if (old !== '') {
                element.val(old);
                element.trigger('change');
            }
        }
    });
    if(cityid == 'LiveCity'){
        if(old !== ''){
            $('#LiveAddress_div').show();
        }
    }
    
    element.trigger('change');
    element.select2({ placeholder: "", });
}
////////////////////////////////// load complete page ////////////////////////////////
load_old_data=function () {
    $('div[id=div_job_select]').addClass('border-danger rounded mb-0');
    //$('div[id=div_job_select]').attr('style','border-width:3px !important;');
    //$('div[id=div_job_select]').remove();
    //showerror("هام","تأكد من اختيار الوظيفة");
    //$('#div_job_info').hide();
    /*
    $('#NIDdiv').remove();
    $('#Ama_id_info').remove();
    $('#fullname_info').remove();
    $('#born_info').remove();
    $('#live_info').remove();
    $('#MaritalStatus_id_info').remove();
    $('#Tamin_info').remove();
    $('#Khebra_info').remove();
    $('#Health_id_info').remove();
    $('#connection_info').remove();
    $('#ed_info').remove();
    $('#Arm_id_info').remove();
    $('review').remove();
    */
            data = MainData['value'];
            console.log(data);
            var uid = $('input[name=uid]').val();
            //var connection=JSON.parse(data['work_jobs_people_info']['connect']);
            /*$('input[name=Fname]').val(data['Fname']);
            $('input[name=Sname]').val(data['Sname']);
            $('input[name=Tname]').val(data['Tname']);
            $('input[name=Lname]').val(data['Lname']);
            $('input[name=ConnectLandline]').val(data['ConnectLandline']);
            $('input[name=ConnectMobile]').val(data['ConnectMobile']);
            $('input[name=ConnectEmail]').val(data['ConnectEmail']);
            load_old_places('born', [data['BornGov'], data['BornCity']]);
            load_old_places('live', [data['LiveGov'], data['LiveCity']]);
            
            $('input[name=EducationYear]').val(data['EducationYear']);
            $('input[name=Khebra]').val(data['Khebra']);
            $('input[name=Tamin]').val(data['Tamin']);
            
            $('input[name=LiveAddress]').val(data['LiveAddress']);
            load_old_statue('Health_id', $('select[name=Health_id]'), data['Health_id']);
            load_old_statue('MaritalStatus_id', $('select[name=MaritalStatus_id]'), data['MaritalStatus_id']);
            load_old_statue('Arm_id', $('select[name=Arm_id]'), data['Arm_id']);
            load_old_statue('Ama_id', $('select[name=Ama_id]'), data['Ama_id']);
            load_old_statue('Education_id', $('select[name=Education_id]'), data['Education_id_id']);
            $('select[name=select_job]').val(data['employment_job']['slug']);
            $('select[name=select_job]').trigger('change');
            $('select[name=select_job]').prop("disabled", true);
            $('#div_job_select').hide();
            */
}

load_old_statue=function (type, element, data) {
    element.attr('old', data);
    functionName = element.attr('data-init-function');
    if (typeof window[functionName] === "function") {
        window[functionName](element);
    }
}

load_old_places=function (type, places) {
    gov = places[0];
    city = places[1];
    if (type === 'born') {
        element_gov = $('select[name=BornGov]');
        element_city = $('select[name=BornCity]');
        element_gov.attr('old', gov);
        element_city.attr('old', city);
        load_default_LiveGov(element_gov)
    } else {
        element_gov = $('select[name=LiveGov]');
        element_city = $('select[name=LiveCity]');
        element_gov.attr('old', gov);
        element_city.attr('old', city);
        load_default_LiveGov(element_gov)
    }




}
/////////////// get all jobs ////////////////
select_job=function (element) {
    if ($('input[name=actiontype]').val() === 'apply') {
        $('#div_job_select').hide();
        return '';
    }
    $annonce_slug = $('#annonce_id').val();
    var joblink = websitelink + '/api/employment/getjob/' + $annonce_slug;
    jQuery.ajax({
        url: joblink,
        method: 'post',
        cache: false,
        dataType: 'json',
        beforeSend:function(){
            $('[data-bs-refresh='+$(element).attr('id')+']').remove();
        },
    }).done(function(data) {
        if(data.hasOwnProperty('error')){
            par=$(element).parent();
            ref=$('<i class="fa fa-refresh text-success" aria-hidden="true" data-bs-refresh="'+$(element).attr('id')+'"></i>')
            $(par).append($(ref))
            $(ref).on('click',function(){
                functionName='select_job';
                window[functionName](element)
            });
            return  ;
        }
        json = data.data;
        $.each(json, function(key, value) {
            if($(element).attr('old') == value['Slug']){
                Selected="selected";
            }else{
                Selected="";
            }
            var group = $('<option value="' + value['Slug'] + '" driver="' + value['Driver'] + '" '+Selected+'/>').html('كود:' + value['code'] + ' - وظيفة ' + value['Mosama_JobNames']['Text']);
            group.appendTo(element);
        }); 
    }).fail(function(jqXHR, textStatus) {
        par=$(element);
        ref=$('<i class="fa fa-refresh text-success" aria-hidden="true" data-bs-refresh="'+$(e).attr('id')+'"></i>')
        $(par).append($(ref))
        $(ref).on('click',function(){
            functionName='select_job';
            window[functionName](element)
        });
    });
    element.select2();
}
////////////////////////////////// load complete page ///////////////////////////////////
////////////////////////////////// load default page /////////////////////////////////////////
////////////////////////////////// on work //////////////////////////////////////
////// check input size  /////////
NID_on_input=function (NID, NIDshow) {
    NID_input = $('#' + NID);
    var NID_val = NID_input.val();
    NIDshow = $('#' + NIDshow);
    NIDshow.html(NID_val.length + '-14');
    if(NID_val.length == 14){
        NID_on_blur(jstrans, 'NID', 'job_id', 'annonce_id', 'NIDreal')
    }
}
function NID_on_blur(trans, NID, jb, an, NIDshow) {
    res = 0;
    NID_input = $('#' + NID);
    var NID_val = NID_input.val();
    var NID_lenght = NID_val.length;
    var btn = $('.swal2-container .swal2-popup .swal2-actions .swal2-confirm');
    var realNID = $('.swal2-container .swal2-popup .swal2-content .swal2-html-container .md-form #NIDreal');
    if (NID_lenght != 14) {
        $('input[name=BirthDate]').val('');
        $('input[name=Sex]').val('');
        $('input[name=AgeYears]').val('');
        $('input[name=AgeMonths]').val('');
        $('input[name=AgeDays]').val('');
        showerror(jstrans['error'], jstrans['nidisnot14']);
    } else {
        // statement
        res += NID_val[0] * 2;
        res += NID_val[1] * 7;
        res += NID_val[2] * 6;
        res += NID_val[3] * 5;
        res += NID_val[4] * 4;
        res += NID_val[5] * 3;
        res += NID_val[6] * 2;
        res += NID_val[7] * 7;
        res += NID_val[8] * 6;
        res += NID_val[9] * 5;
        res += NID_val[10] * 4;
        res += NID_val[11] * 3;
        res += NID_val[12] * 2;
        res = res % 11;
        res = 11 - res;
        if (res > 9) res = res % 10;
        if (res == NID_val[13]) {
            job_input = $('#' + jb);
            annonce_input = $('#' + an);
            var job_val = job_input.val();
            var annonce_val = annonce_input.val();
            var Website_link = websitelink + '/api/employment/checknid/' + annonce_val + "/" + job_val;
            var actiontype=$('input[name=actiontype]').val();
      datalist={page:actiontype,annoncestage:'D:3',nid:NID_val}
            jQuery.ajax({
                url: Website_link,
                dataType: 'json',
                data:datalist,
                type: 'post',
                success: function(data) {
                    if (data['result'] == 'success') {
                        //٢٩٠٠١٠١١٢٧٠٣٢٣
                        NID_val = NID_val.split('');
                        var yr = ((NID_val[0] * 100) + 1700) + (NID_val[1] * 10) + (NID_val[2] * 1);
                        var dt = "";
                        dt = yr + "-" + NID_val[3] + NID_val[4] + "-" + NID_val[5] + NID_val[6];
                        document.getElementById('BirthDate').value = dt;
                        view_noty('success', jstrans['bd_applyed'] + dt);
                        age = getAge(dt);
                        document.getElementById('AgeYears').value = age['years'];
                        document.getElementById('AgeMonths').value = age['months'];
                        document.getElementById('AgeDays').value = age['days'];
                        view_noty('success', jstrans['age_applyed'] + age['years'] + ' - ' + age['months'] + ' - ' + age['days']);
                        if (NID_val[12] % 2 == 0) {
                            document.getElementById('Sex').value = 0;
                            view_noty('success', jstrans['sex'] + ':انثى');
                        } else {
                            document.getElementById('Sex').value = 1;
                            view_noty('success', jstrans['sex'] + ':ذكر');
                        }
                        view_noty('success', jstrans['nidtestSuccess']);
                    } else if (data['result'] === 'not14') {
                        showerror(jstrans['nidisnot14'], jstrans['nidisnot14']);
                    } else if (data['result'] === 'errorannonce') {
                        showerror('من فضلك تأكد من بيانات الاعلان', 'من فضلك تأكد من بيانات الاعلان');
                    } else if (data['result'] === 'errorjob') {
                        showerror('من فضلك تأكد من بيانات الوظيفة', 'من فضلك تأكد من بيانات الوظيفة');
                    } else if (data['result'] === 'isset') {
                        showerror('', jstrans['nidIssetBefore']);
                    } else {
                        showerror('من فضلك راسلنا على البريد الاليكترونى<br>اضف رابط الصفحة واكتب الخطوات التى قمت بها', '');
                    }
                }
            });
        } else {
            view_noty('error', jstrans['nidisnot14']);

        }
    }

}
////////////////////////////////// on work //////////////////////////////////////
////////////////////////////////// trigers /////////////////////////////////////////
// set job input data when select
setjobfrommenue=function () {
    //set hidden NID_on_input
    select = $('select[name=select_job]');
    var selectval = select.val();
    $('#hiddens').attr('jb', selectval);
    $('input[name=job_id]').val(selectval);
    load_ann_job_info();
}
beforeclick=function (element) {
    var arrayOfInputNames, inputs, L, ch, selects, arrayOfSelectNames, M;
    job_val = $('select[name=select_job]').val();
    var listforcheck={};
    var ch = [];
    var arrayOfInputNames = [];
    var arrayOfSelectNames = [];
    var inputs = document.getElementsByTagName("input");
    var selects = document.getElementsByTagName("select");
    selelmts = document.getElementsByTagName("select");
    L = inputs.length;
    for (var i = 0; i < L; i++) {
        if (inputs[i].type !== 'hidden') {
            arrayOfInputNames.push(inputs[i].name);
        }
    }
    M = selects.length;
    for (var i = 0; i < selects.length; i++) {
        arrayOfSelectNames.push(selects[i].name);
    }
    if (window.Amer.apply.accept_driver === 1) {
        arrayOfInputNames.splice(jQuery.inArray("DriverEnd", arrayOfInputNames), 1);
        arrayOfInputNames.splice(jQuery.inArray("DriverStart", arrayOfInputNames), 1);
        arrayOfSelectNames.splice(jQuery.inArray("DriverDegree", arrayOfSelectNames), 1);
    }

    arrayOfInputNames.forEach(function(a, b) {
        var attr = $('input').attr('name');
        if (typeof attr !== 'undefined' && attr !== false) {
            var input = $('input[name=' + a + ']');
            var input_val = input.val();
            if (input.attr('type') === 'file') {
                if (input.val() === '') {
                    input.addClass('border border-danger');
                    ch.push('<span errorinput="'+a+'">'+input.attr('placeholder')+'</span>');
                }
            } else if (input.attr('type') === 'checkbox') {
                cb = document.getElementById(input.attr('id'));
                if (!cb.checked) {
                    input.addClass('border border-danger');
                    ch.push('<span errorinput="'+a+'">'+input.attr('placeholder')+'</span>');
                }
            }
            
            //console.log(a + ': ' + input_val + ' : ' + input_val.length);
            var input_val_l = input_val.length;
            var input_min = input.attr('minlen');
            if (input_val_l < input_min) {
                input.addClass('border border-danger');
                ch.push('<span errorinput="'+a+'">'+input.attr('placeholder')+'</span>');
            } else {
                input.removeClass('border border-danger');
            }
            listforcheck[a]=input_val;
    }
    });
    arrayOfSelectNames.forEach(function(a, b) {
        var select = $('select[name=' + a + ']');
        var select_val = select.val();
        listforcheck[a]=select_val;
        if ((select_val === null) || (select_val === '')) {
            select.addClass('border border-danger');
            ch.push('<span errorinput="'+a+'">'+select.attr('placeholder')+'</span>');
        } else {
            select.removeClass('border border-danger');
        }
    });
    $uploadinput = document.getElementById('inputGroupFile01');
    if ($uploadinput.files.length !== 1) {
        ch.push('<span errorinput="uploades">'+$('input[name=uploades]').attr('placeholder')+'</span>');
    } else {
        $uploadedfile = $uploadinput.files.item(0);
        if ($uploadedfile.type !== 'application/pdf') {
            ch.push('<span errorinput="uploades">'+jstrans['uploades_type']+'</span>');
        }
        if ($uploadedfile.size > 8388608) {
            ch.push('<span errorinput="uploades">'+jstrans['uploades_size_6000']+'</span>');
        }
    }
    if (ch.length !== 0) {
        var err = '';
        ch.forEach(function(a, b) {
            err += a + '<br>';
        });
        showerror('من فضلك اكمل البيانات', err);
        return '';
    }
    var element_id = element.attr('id');
    //var oper=$()
    var actiontype = $('input[name=actiontype]').val();
    var link = window.location.href + '/' + actiontype + '/' + element_id;
    ///test from api
    if (element_id === 'review') {
        $('form[id=ENTRYFORM]').attr('target', '_blank')
        $('form[id=ENTRYFORM]').attr('method', 'POST')
        $('form[id=ENTRYFORM]').attr('action', link)
        $('form[id=ENTRYFORM]').submit();
    } else {
        $('form[id=ENTRYFORM]').attr('target', '_self')
        check=beforeclickApi(listforcheck);
    }
    
}
beforeclickApi=function(data){
    var inputlist=new FormData();
    var inputs=$('form[id=ENTRYFORM] :input');
    $.each(inputs,function(k,v){
        if($(v).attr('name') !== undefined){
            value=$(v).val();
            inputname=$(v).attr('name');
            if(inputname == 'uploades'){
                    const fileInput = document.querySelector('input[name="uploades"]'); 
                    const file = fileInput.files[0]; 
                    inputlist.append('uploades', file, file.name)
            }else{
                inputlist.append(inputname,value);
            }
            
        }
    });
    annonce_id=inputlist.get('annonce_id')
    console.log(inputlist);
    actiontype=inputlist.get('actiontype')
    job_id=inputlist.get('job_id')
    var link=[websitelink,'api','employment_operation','stage',annonce_id,job_id,actiontype,'check'].join('/')
    jQuery.ajax({
        url: link,
        type: 'post',
        cache: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        data:inputlist,
        beforeSend:function(){
            loader_div('','dodds')
        },
    }).done(function(jsonObject) {
        remove_loader_div('dodds')
        objectkeys=Object.keys(jsonObject)
        if(objectkeys.includes('error')){
            $shr = showallerrors(jsonObject.error);
            $ti = '';
            if ($shr[0] === 'message') { $ti = ''; }
            if ($shr[0] === 'input') { $ti = jstrans['pleasefillinputs']; }
            $shr[0] = '';
            showerror( $shr);
        }else{
            var actiontype = $('input[name=actiontype]').val();
            var link = window.location.href + '/' + actiontype + '/' + actiontype +'-review';
            var id=$('<input name="test" type="text" value="'+jsonObject['success'][0]+'">')
            var test=$('<input name="id" type="text" value="'+jsonObject['success'][1]+'">')
            $('form[id=ENTRYFORM]').append(id);
            $('form[id=ENTRYFORM]').append(test);
            $('form[id=ENTRYFORM]').attr('target', '_self')
            $('form[id=ENTRYFORM]').attr('method', 'POST')
            $('form[id=ENTRYFORM]').attr('action', link)
            $('form[id=ENTRYFORM]').submit();
        }
    }).fail(function(jqXHR, textStatus) {
        remove_loader_div('dodds')
    });

    
}
////////////////////////////////// trigers /////////////////////////////////////////
/////////////////////////////////   helpers //////////////////////////////////////////////////
trim=function (id) {
    if (id != null)
        id.value = id.value.toString().replace(/^\s+|\s+$/g, "");
}
// set options and optgroup
set_opt_group_optioss=function (element, id, li, dd,data) {
    var old = element.attr('old');
    element.select2({
        cache: true
    })
    jsonObject=data;
        jsonObject.forEach(function(item, index) {
            if (item['children'] === undefined) {
                var count = 0;
            } else {
                var count = Object.keys(item['children']).length;
            }
            var opt = document.createElement('option');
            if (count === 0) {
                if (old == item['id']) { var $option = $("<option selected></option>").val(item['id']).text(item[dd]); } else { var $option = $("<option></option>").val(item['id']).text(item[dd]); }
                element.append($option);
            } else {
                var optgroup = $('<optgroup label="' + item[dd] + '"/>').html(item[dd]);
                element.append(optgroup);
                item['children'].forEach(function(items, indexs) {
                    if (old == items['id']) { var $option = $("<option selected></option>").val(items['id']).text(items[dd]); } else { var $option = $("<option></option>").val(items['id']).text(items[dd]); }
                    element.append($option);
                });
            }
        });
}
getAge=function(dateString) {
    var wantedage = $('.job_info_age').html();
    var wantedagein = $('.wantedagein').html();
    //var now =Date.now();
    var now=new Date();
    var yearNow = now.getYear();
    var monthNow = now.getMonth()+1;
    var dateNow = now.getDate();
    var dob = new Date(dateString.substring(0, 4),
        dateString.substring(5, 7) - 1,
        dateString.substring(8, 10)
    );
    var yearDob = dob.getYear();
    var monthDob = dob.getMonth();
    var dateDob = dob.getDate();
    var age = {};
    var ageString = "";
    var yearString = "";
    var monthString = "";
    var dayString = "";
    yearAge = yearNow - yearDob;
    if (monthNow >= monthDob)
        {
            var monthAge = monthNow - monthDob;
        }
    else {
        yearAge--;
        var monthAge = 12 + monthNow - monthDob;
    }

    if (dateNow >= dateDob)
        var dateAge = dateNow - dateDob;
    else {
        monthAge--;
        var dateAge = 31 + dateNow - dateDob;

        if (monthAge < 0) {
            monthAge = 11;
            yearAge--;
        }
    }

    age = {
        years: yearAge,
        months: monthAge,
        days: dateAge
    };
    return age;
}

$('form').ready(function() {
    $('#NID').on('blur',function(){NID_on_blur(jstrans, 'NID', 'job_id', 'annonce_id', 'NIDreal')})
    $('#BornGov').on('change', function() { load_default_City(jstrans, 'BornGov', 'BornCity'); });
    $('#LiveGov').on('change', function() { load_default_City(jstrans, 'LiveGov', 'LiveCity'); });
    $('#LiveCity').on('change', function() { $('#LiveAddress_div').show(); });
    $('#select_job').on('change', function() { setjobfrommenue(); });
    $('#Khebra_type').on('change', function() { setkhebraType(); });
    
    //accept_driver = '';
    
});
$('form').ready(function() {
    $('input[type="text"]').on('keyup',function(e){window.arabicKeyboard(e);});
    $('.select2-search__field').on('keyup',function(e){window.arabicKeyboard(e);});
    $('textarea').on('keyup',function(e){window.arabicKeyboard(e);});
    $('input[type="number"]').on('keyup',function(e){window.toEnglishNumber(e);});
    $('input[type="tel"]').on('keyup',function(e){window.toEnglishNumber(e);});    
});
})(jQuery)
