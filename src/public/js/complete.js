$('form').ready(function() {
    $('input[type="text"]').on('keyup',function(){
    $(this).val($(this).val().replace(/[`]/g, "ذ"));
    $(this).val($(this).val().replace(/~/g, "ذ"));
    $(this).val($(this).val().replace(/۰/g, "0"));
    $(this).val($(this).val().replace(/۱/g, "1"));
    $(this).val($(this).val().replace(/۲/g, "2"));
    $(this).val($(this).val().replace(/۳/g, "3"));
    $(this).val($(this).val().replace(/٤/g, "4"));
    $(this).val($(this).val().replace(/۵/g, "5"));
    $(this).val($(this).val().replace(/٦/g, "6"));
    $(this).val($(this).val().replace(/۷/g, "7"));
    $(this).val($(this).val().replace(/۸/g, "8"));
    $(this).val($(this).val().replace(/۹/g, "9"));
    $(this).val($(this).val().replace(/۰/g, "0"));
    $(this).val($(this).val().replace(/q/g, "ض"));
    $(this).val($(this).val().replace(/w/g, "ص"));
    $(this).val($(this).val().replace(/e/g, "ث"));
    $(this).val($(this).val().replace(/r/g, "ق"));
    $(this).val($(this).val().replace(/t/g, "ف"));
    $(this).val($(this).val().replace(/y/g, "غ"));
    $(this).val($(this).val().replace(/u/g, "ع"));
    $(this).val($(this).val().replace(/i/g, "ه"));
    $(this).val($(this).val().replace(/o/g, "خ"));
    $(this).val($(this).val().replace(/p/g, "ح"));
    $(this).val($(this).val().replace(/\[/g, "ج"));
    $(this).val($(this).val().replace(/\]/g, "د"));
    $(this).val($(this).val().replace(/a/g, "ش"));
    $(this).val($(this).val().replace(/s/g, "س"));
    $(this).val($(this).val().replace(/d/g, "ي"));
    $(this).val($(this).val().replace(/f/g, "ب"));
    $(this).val($(this).val().replace(/g/g, "ل"));
    $(this).val($(this).val().replace(/h/g, "ا"));
    $(this).val($(this).val().replace(/j/g, "ت"));
    $(this).val($(this).val().replace(/k/g, "ن"));
    $(this).val($(this).val().replace(/l/g, "م"));
    $(this).val($(this).val().replace(/\;/g, "ك"));
    $(this).val($(this).val().replace(/\'/g, "ط"));
    $(this).val($(this).val().replace(/z/g, "ئ"));
    $(this).val($(this).val().replace(/x/g, "ء"));
    $(this).val($(this).val().replace(/c/g, "ؤ"));
    $(this).val($(this).val().replace(/v/g, "ر"));
    $(this).val($(this).val().replace(/b/g, "لا"));
    $(this).val($(this).val().replace(/n/g, "ى"));
    $(this).val($(this).val().replace(/m/g, "ة"));
    $(this).val($(this).val().replace(/\,/g, "و"));
    $(this).val($(this).val().replace(/\./g, "ز"));
    $(this).val($(this).val().replace(/\//g, "ظ"));
    $(this).val($(this).val().replace(/~/g, " ّ"));
    $(this).val($(this).val().replace(/Q/g, "َ"));
    $(this).val($(this).val().replace(/W/g, "ً"));
    $(this).val($(this).val().replace(/E/g, "ُ"));
    $(this).val($(this).val().replace(/R/g, "ٌ"));
    $(this).val($(this).val().replace(/T/g, "لإ"));
    $(this).val($(this).val().replace(/Y/g, "إ"));
    $(this).val($(this).val().replace(/U/g, "‘"));
    $(this).val($(this).val().replace(/I/g, "÷"));
    $(this).val($(this).val().replace(/O/g, "×"));
    $(this).val($(this).val().replace(/P/g, "؛"));
    $(this).val($(this).val().replace(/A/g, "ِ"));
    $(this).val($(this).val().replace(/S/g, "ٍ"));
    $(this).val($(this).val().replace(/G/g, "لأ"));
    $(this).val($(this).val().replace(/H/g, "أ"));
    $(this).val($(this).val().replace(/J/g, "ـ"));
    $(this).val($(this).val().replace(/K/g, "،"));
    $(this).val($(this).val().replace(/L/g, "/"));
    $(this).val($(this).val().replace(/Z/g, "~"));
    $(this).val($(this).val().replace(/X/g, "ْ"));
    $(this).val($(this).val().replace(/B/g, "لآ"));
    $(this).val($(this).val().replace(/N/g, "آ"));
    $(this).val($(this).val().replace(/M/g, "’"));
    $(this).val($(this).val().replace(/\?/g, "؟"));
});
            set_current_date_time('apply_date');
        load_ann_job_info('annonce_id','job_id');

    $('#nid').on('input',function(){nid_on_input('nid','nidreal');});
    $('#nid').on('blur',function(){nid_on_blur(jv_errors,'nid','job_id','annonce_id','nidreal');});
    $('#born_gov').on('change',function(){view_city_menu(jv_errors,'born_gov','born_city');});
    $('#live_gov').on('change',function(){view_city_menu(jv_errors,'live_gov','live_city');});
    $('#live_city').on('change',function(){$('#live_add_div').show();});

        checkinput();
        $('input[name="edu_year"]').datepicker({
        format: "M-yyyy",
        language: "ar",
        multidate: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true
    });
    check_reqerrors();
});
$('.previewinput').remove();
//$('.applyinput').remove();
$('form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  })
  function checkinput(){
    var edu_year=$('input[name="edu_year"]');
    edu_year.on('focusout',function(){
      check_focus_empty(edu_year,1);
    });
    var khebra=$('input[name="khebra"]');
    khebra.on('focusout',function(){
      check_focus_empty(khebra,1);
    });
    var malaftaminy=$('input[name="malaftaminy"]');
    malaftaminy.on('focusout',function(){
      check_focus_empty(malaftaminy,1);
    });
}
function check_focus_empty(name,sd){
    if(name.val().length < sd){
      name.addClass('border border-danger');
    }
    if(name.val().length > sd){
      name.removeClass('border border-danger');
    }
  }
  function check_reqerrors(){
    var element=$('.errors');
    var alop=element.attr('alop');
    if(alop === ''){return '';}
    $alop=JSON.parse(alop);
    for(var k in $alop){
        //alert($alop[k]);
        $('*[name='+$alop[k]+']').parent().addClass('danger-color');
    }
}

function beforeclick(element){
    id=element.attr('id');
    var required_text_input=Array(
        'edu_year',
    );

    var require_select2=Array(
        'education',
    );
    var emptyval=[];
    var nemptyvals=[];
    if($('input:checkbox').is(':checked')){
        nemptyvals.push('acceptall');
        }else{
            emptyval.push($('input:checkbox').attr('placeholder'));
        }
    require_select2.forEach(function(item,index){
        if($('select[name='+item+']').val() == ''){
            emptyval.push($('select[name='+item+']').attr('placeholder'));
        }else{
            nemptyvals.push(item);
        }
    });
    required_text_input.forEach(function(item,index){
        if($('input[name='+item+']').val() == ''){
            emptyval.push($('input[name='+item+']').attr('placeholder'));
        }else{
            nemptyvals.push(item);
        }
    });

    if(emptyval.length !== 0){
        res='<ul>';
        emptyval.forEach(function(item,index){
            res+='<li>'+item+'</li>';
        });
        res+='</ul>';
        showerror(jv_errors['pleasefillinputs'],res);
        return'';
    }
    if(element.attr('id') == 'review'){
        getval=$('.readyforsend').html();
    winopenlink=website+'/employment_operation/preview/'+$('input[id=annonce_id]').val()+"/"+$('input[id=job_id]').val()+"?"+getval;
    window.open(winopenlink,'_blank');
    }
    if(element.attr('id') == 'submit'){
        submitapply();
    }
}

function submitapply()
{alert('s');
    var inputs=Array(
        '_token',
        '_method',
        'annonce_id',
        'job_id',
        'edinid',
        'edu_year',
        'education',
    );
      var f = $('form');
    var vals=Array();
inputs.forEach(function(item,index){
    //f['gender_id'].value=$('input[name='+item+']').val()
    vals.push($('input[name='+item+']').val());
})
$('form').attr('action',location+'/complete');
$('form').submit();
//window.open('','_self');
//
}
function set_current_date_time(clas)
{
    var dd = new Date().toISOString().slice(0, 19).replace('T', ' ');
            var inputF = document.getElementById(clas);
            inputF.value = dd;
}
function load_ann_job_info(an,jb)
{
    $annonce_slug=$('#'+an).val();
    $job_slug=$('#'+jb).val();
    joblink=websitelink+'/api/employment/getjob/'+$annonce_slug+'/'+$job_slug+'/';
    jQuery.ajax({
        url:joblink,
        dataType: 'json',
        type: 'get',
        success: function(data) {
            set_ann_job_info(data);
        },
        error: function (e,xhr,opt) {
            showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });
}
function set_ann_job_info(data){
    var json = data;
    if(json.length !== 1){$('form').remove();}
    $job=json[0]['export_work_jobs'][0];
    $annonce=json[0];
    /*
     * driver
     */

    setspan('.info_title_functional_annonce_number',$annonce['number']);
    setspan('.info_title_functional_annonce_number_foryear',$annonce['year']);
    setspan('.info_title_functional_annonce_desc',$annonce['description']);
    setspan('.info_title_functional_annonce_place',$annonce['place']['gov'],'','-');
    setspan('.info_title_functional_annonce_qual',$annonce['qualifications'],0,'<br>');
    setspan('.info_title_name',$job['job_name']);
    setspan('.info_title_jobname',$job['name']);
    setspan('.info_job_description',$job['job_description']);
    setspan('.info_title_functional_class',$job['work_jobs_functional_class']['category']);
    setspan('.info_jobcode',$job['code']);
    setspan('.info_job_addadmatlob',$job['count']);
    setspan('.info_job_khebrayears',$job['khebra']);
    setspan('.info_idcity',$job['city']['city'],'','-');
    edu=$job['education'];
    var edv='';
    $.each(edu,function(l,m){
        edv+=m['son']+'<br>';
    });
    setspan('.info_edu',edv);
    setspan('.info_instructions',$job['instructions'],'text','<br>');
    setspan('.info_title_functional_job_qual',$job['qualifications'],0,'<br>');
    setspan('.job_info_age',$job['age']);
    setspan('.job_info_age_date',$job['age_in']);
    setspan('.included_files',$job['included_files'],'filename','<br>');
    setspan('.agreement',$job['instructions'],'text','<br>');
 }
function load_default_live_gov(element){
    var gov_id=element.attr('id');
    var city_id=element.attr('next');
    var old=element.attr('old');
	var $ID=element.attr('id');

	fuapilink=websitelink+'/api/employment/allgovs';
	values=element.attr('vl');
	show=element.attr('sh');
	jQuery.ajax({
		url: fuapilink,
		method:'GET',
		cache:false,
		dataType:'json',
	}).done(function( html ) {
		json=html;
        $.each(json,function (key,value) {
            var group = $('<option value="' + value[values] + '" />').html(value[show]);
            group.appendTo(element);
        });
        if(old !== ''){
            $('#'+gov_id).val(old);
            $('#'+city_id).select2();
            $('#'+city_id).show();
            $('#'+gov_id).trigger('change');
        }
  }).fail(function( jqXHR, textStatus ) {
  $('form').remove();
});
  $('#'+$ID).select2({
		placeholder: "",
    });
}
function view_city_menu(jv_errors,govid,cityid)
{
    var gov_element=$('#'+govid);
	var selected_gov=gov_element.find(':selected').val();
    var old=$('#'+cityid).attr('old');
	var element=$('#'+cityid);
	var fuapilink=websitelink+'/api/employment/cities_by_gov/'+selected_gov;
	var values=element.attr('vl');
	var show=element.attr('sh');
	element.empty();
	var emptyoption = $('<option/>').html('');
    emptyoption.appendTo(element);
	jQuery.ajax({
		url: fuapilink,
		method:'GET',
		cache:false,
		dataType:'json',
	}).done(function( html ) {
		json=html;
   $.each(json,function (key,value) {
   	var group = $('<option value="' + value[values] + '" />').html(value[show]);
    group.appendTo(element);
   });
   if(old !== ''){
        element.val(old);
        element.trigger('change');
    }
  }).fail(function( jqXHR, textStatus ) {
  $('form').remove();
});
  element.select2({placeholder: "",});
}
function set_edu(element){
    id=element.attr('id');
    li=element.attr('option');
    dd=element.attr('dd');
    set_opt_group_optioss(element,id,li,dd);
}
function sethealth(element){
    id=element.attr('id');
    li=element.attr('option');
    dd=element.attr('dd');
    set_opt_group_optioss(element,id,li,dd);
}
function set_mir(element){
    id=element.attr('id');
    li=element.attr('option');
    dd=element.attr('dd');
    set_opt_group_optioss(element,id,li,dd);
}
function set_arm(element){
    id=element.attr('id');
    li=element.attr('option');
    dd=element.attr('dd');
    set_opt_group_optioss(element,id,li,dd);
}
function set_ama(element){
    id=element.attr('id');
    li=element.attr('option');
    dd=element.attr('dd');
    set_opt_group_optioss(element,id,li,dd);
}
function set_opt_group_optioss(element,id,li,dd){
    var old=element.attr('old');
    element.select2({
        cache:true
    })
    $.ajax({
     url: websitelink+'/api/'+li,
     type: 'get',
     dataType: 'json',
     success: function (jsonObject){
        jsonObject.forEach(function(item,index){
            if(item['children'] === undefined){
                var count = 0;
            }else{
              var count = Object.keys(item['children']).length;
            }
              var opt = document.createElement('option');
              if(count === 0){
                    var $option = $("<option></option>").val(item['id']).text(item[dd]);
                    element.append($option);
            }else{
                    var optgroup = $('<optgroup label="'+item[dd]+'"/>').html(item[dd]);
                    element.append(optgroup);
                        item['children'].forEach(function(items,indexs){
                            if(old == items['id']){var $option = $("<option selected></option>").val(items['id']).text(items[dd]);}else{var $option = $("<option></option>").val(items['id']).text(items[dd]);}
                            element.append($option);
                        });
            }
        });
     }
});
}
