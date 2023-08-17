function IsValidJSONString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
function starts(sitelink){
     old_am=get_old('am');
     if(old_am !== ''){select_ann_type(sitelink,old_am);}else{return false;}
     old_he=get_old('he');
     if(old_he !== ''){select_annonces(sitelink,old_am,old_he);}else{return false;}
     old_al=get_old('al');
     if(old_al !== ''){select_job(sitelink,old_am,old_he,old_al);}else{return false;}
     old_res=get_old('res');     
     if(old_res !== ''){result_type(old_res);}else{return false;}
     old_email=get_old('email');
     if(old_email !== ''){get_people(sitelink,old_am,old_he,old_al,old_res,old_email);}else{return false;}
     return true;
     //alert(jQuery.parseJSON(vo));
    //select_message(sitelink,old=null);
 
}
function get_old(v){
    return $('#'+v).attr('old');
}
function get_selected(element){
    return $('#'+element).find(':selected').val();    
}
  function setupselect2(vocal){
    return $('select[data-init-function="'+vocal+'"]').select2();
  }
  function select_message(url){
      var URL=url+"admin/work_jobs_people/getemailsviews";
    jQuery.ajax({
        url:URL,
        dataType: 'json',
        type: 'get',
        success: function(data) {
          nr=$('select#message_template');
          var group='<option></option>';
          $.each(data,function (key,value) {
            group+='<option value="'+value['id']+'">'+value['name']+'</option>';
          });
          nr.append(group);
        },
        error: function (e,xhr,opt) {
          $('#demo').html("Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        }
      });
  }
  function select_ann_type(url,old=null){
    if(old == null){$am_old=$('#am').attr('old');}else{$am_old=old;}
    nr_am=$('select#am');
    var group_am='<option></option>';
    group_am+='<option value="0" ';
    if($am_old == '0'){group_am+='SELECTED';}
    group_am+='>اعلان مفتوح</option>';
    group_am+='<option value="1" ';
    if($am_old == '1'){group_am+='SELECTED';}
    group_am+='>اعلان مغلق</option>';
    nr_am.append(group_am);
    $('#am').trigger( "change", [ "change", "Event" ] );
  }
  
  function select_annonces(url,am_val=null,old=null){
    if(am_val == null){am_val=$('#am').find(':selected').val();}
    if(old == null){}
    weblink=url+"admin/work_jobs_people/preparetoexportxml";
    jQuery.ajax({
      url:weblink,
      data:{'am':am_val},
      dataType: 'json',
      type: 'get',
      success: function(data) {
        $('#demo').html(data);
        //var json = JSON.parse(data);
        nr=$('select#he');
        var group='<option></option>';
        $.each(data,function (key,value) {
            group+='<option value="'+value['id']+'"';
            if(old == value['id']){
                group+=' selected';
            }
            group+='>اعلان رقم:'+value['number']+' لسنة:'+value['year']+'</option>';
        });
      if(old == null){
        $("#he option").remove();
        $("#al option").remove();
        $("#res option").remove();
        $("#email option").remove();
      }
      
        nr.append(group);
      },
      error: function (e,xhr,opt) {
        $('#demo').html("Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        console.log(opt);
      }
    });
  }
  function select_job(sitelink,old_am=null,old_he=null,old_al=null){
    if(old_am !== null){am_val=old_am;}else{am_val=$('#am').find(':selected').val();}
    if(old_he !== null){he_val=old_he;}else{he_val=$('#he').find(':selected').val();}
    if(old_al !== null){$al_old = old_al;}else{al_val=$('#al').find(':selected').val();}
    weblink=sitelink+"admin/work_jobs_people/preparetoexportxml";
    jQuery.ajax({
      url:weblink,
      data:{'am':am_val,'he':he_val},
      dataType: 'json',
      type: 'get',
      success: function(data) {
        $('#demo').html(data);
        //var json = JSON.parse(data);
        nr=$('select#al');
        var group='<option></option>';
        $.each(data,function (key,value) {
            group+='<option value="'+value['id']+'"';
            if(typeof $al_old !== 'undefined'){
              if($al_old == value['id']){
                  group+=' selected';
              }
            }
            group+='>'+value['name']+' - '+value['job_name']+'</option>';
        });
        if(old_al == null){
          $("#al option").remove();
          $("#res option").remove();
          $("#email option").remove();
        }
        nr.append(group);
      },
      error: function (e,xhr,opt) {
        alert("Sdfsd");
        $('#demo').html("Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        console.log(opt);
      }
    });
  }
  function result_type(old_res=null){
    if(old_res !== null){$res_old=old_res;}
    nr=$('select#res');
    var group='<option></option>';
    group+='<option value="0"';if(typeof $res_old !== 'undefined'){if($res_old == '0'){ group+=" selected";}}group+='>مقبول</option>';
    group+='<option value="1"';if(typeof $res_old !== 'undefined'){if($res_old == '1'){ group+=" selected";}}group+='>غير مقبول</option>';
    group+='<option value="2"';if(typeof $res_old !== 'undefined'){if($res_old == '2'){ group+=" selected";}}group+='>كافة النتائج</option>';
    if(old_res == null){
      $("#res option").remove();
      $("#email option").remove();
  } 
    nr.append(group);
   
  }
  function get_people(sitelink,old_am,old_he,old_al,old_res,old_email){
    if(old_am !== null){am_val=old_am;}else{am_val=$('#am').find(':selected').val();}
    if(old_he !== null){he_val=old_he;}else{he_val=$('#he').find(':selected').val();}
    if(old_al !== null){al_val=old_al;}else{al_val=$('#al').find(':selected').val();}
    if(old_res !== null){res_val=old_res;}else{res_val=$('#res').find(':selected').val();}
    weblink=sitelink+"admin/work_jobs_people/getpeople";
    jQuery.ajax({
      url:weblink,
      data:{
        'am':am_val,
        'he':he_val,
        'al':al_val,
        'res':res_val
      },
      dataType: 'json',
      type: 'get',
      success: function(data) {
        $('#demo').html(data);
        //var json = JSON.parse(data);
        nr=$('select#email');
        var group='';
        $.each(data,function (key,value) {
          group+='<option value="';
          group+=value['uid'];
          group+='">';
          group+=value['name']+' - '+value['nid']+' - '+value['email'];
          group+='</option>';
        });
      $("#email option").remove();
        nr.append(group);
        $("#email").val(old_email).trigger('change');

      },
      error: function (e,xhr,opt) {
        $('#demo').html("Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
        console.log(opt);
      }
    });
    $('#email').val('1');
    $('#email').trigger('change'); // Notify any JS components that the value changed
  }
  function select_message_template(sitelink){
    message_template_val=$('#message_template').find(':selected').val();  
      weblink=sitelink+"admin/work_jobs_people/emailstemplatetetarea";
      jQuery.ajax({
        url:weblink,
        data:{
          'message_template_val':message_template_val
        },
        dataType: 'script',
        type: 'get',
        success: function(data) {
          $('#demo').html('');
          //alert(data);
          //$('#editor1').value=data;
          CKEDITOR.instances['editor1'].setData(data);
        },
        error: function (e,xhr,opt) {
          $('#demo').html("Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
          console.log(opt);
        }
      });
  }
  function send_data(sitelink){
    am_val=$('#am').find(':selected').val();
    he_val=$('#he').find(':selected').val();
    al_val=$('#al').find(':selected').val();
    res_val=$('#res').find(':selected').val();
    email_val=$('#email').val();
    editor1_val=CKEDITOR.instances.editor1.getData();
    if(
      (am_val == '') || (he_val == '') || (al_val == '') || (res_val == '') || (email_val == '') || (editor1_val == '')
      ){alert("من فضلك املاء البيانات المطلوبة");}else{
      $('#demo').html();
        for(var i=0; i<email_val.length;i++){
          as=$('#email').select2('data')[i].text;
          current_inti=i;
          var datas=new Array(sitelink+"admin/email/sendemail",am_val,he_val,al_val,res_val,email_val[i],editor1_val,as,current_inti);
          sendemail(datas);
        }
      }
    
      
  }
  function sendemail(datas){
    var weblink=datas[0];
    var am_val=datas[1];
    var he_val=datas[2];
    var al_val=datas[3];
    var res_val=datas[4];
    var email_val=datas[5];
    var editor1_val=datas[6];
    var as=datas[7];
    var current_int=datas[8];
    
    var loadingicon='<div class="spinner-border text-primary" id="sendmailloadingicon_'+current_int+'" role="status">';
      loadingicon+='<span class="sr-only">Loading...</span>';
      loadingicon+='</div>';
    $('#demo').append('<div id="sendmailresult_'+current_int+'" class="border">'+loadingicon+as+'</div>');
    $.ajax(
    {
      url:weblink,
      dataType:'html',
      data:{
      'am' : am_val,
      'he' : he_val,
      'al' : al_val,
      'res' : res_val,
      'email' : email_val,
      'editor1' : editor1_val,
    },
      type:'POST',
      success: function(data){
        $('#sendmailloadingicon_'+current_int).removeClass('spinner-border');
        if(data === '1'){
          $('#sendmailloadingicon_'+current_int).html('<span class="fa fa-check"></span>');
        }else{
          $('#sendmailloadingicon_'+current_int).removeClass('text-primary');
          $('#sendmailloadingicon_'+current_int).addClass('text-danger');
          $('#sendmailloadingicon_'+current_int).html('<span class="fa fa-times"></span>');
        }
      }
    }
  );
  }
  function preview(sitelink){
    $erro='';
      //get am data
      am_val=$('#am').find(':selected').val();
      he_val=$('#he').find(':selected').val();
      al_val=$('#al').find(':selected').val();
      res_val=$('#res').find(':selected').val();
      email_val=$('#email').val();
      editor1_val=CKEDITOR.instances.editor1.getData();
      weblink=sitelink+"admin/email/appliedpreview";
      $.ajax(
        {
          url:weblink,
          dataType:'html',
          data:{
          'am' : am_val,
          'he' : he_val,
          'al' : al_val,
          'res' : res_val,
          'email' : email_val,
          'editor1' : editor1_val,
        },
          type:'POST',
          success: function(data){
            $('#demo').html(data);
            $('select[data-init-function="resultselect"]').select2();
          }
        }
      );
  }