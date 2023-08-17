Noty.overrideDefaults({
    layout   : 'topRight',
    theme    : 'backstrap',
    timeout  : 2500,
    closeWith: ['click', 'button'],
  });
$(document).on("keypress",".select2-input",function(event){
    if (event.ctrlKey || event.metaKey) {
        var id =$(this).parents("div[class*='select2-container']").attr("id").replace("s2id_","");
        var element =$("#"+id);
        if (event.which == 97){
            var selected = [];
            element.find("option").each(function(i,e){
                selected[selected.length]=$(e).attr("value");
            });
            element.select2("val", selected);
        } else if (event.which == 100){
            element.select2("val", "");
        }
    }
});
$( document ).ready(
    function(){
        removeAllOptions(document.getElementById('annonce'));
        set_ann_name();
    });
    $('#annonce').change(function(){
        loadfromapi();
    });
function loader_div(target,id){
    html='';
        html+='<div id="loader" class="container-fluid d-flex justify-content-center full-width-div" area="'+id+'">';
        html+='<div class="my-auto">';
            html+='<div class="spinner-border" role="status">';
                html+='<span class="sr-only">Loading...</span>';
            html+='</div>';
        html+='</div>';
    html+='</div>';
    $('body').prepend(html);
}
function remove_loader_div(id){

    $('div[area='+id+']').remove();

}
function set_ann_name(){
    var link=sitelink+'/api/admin/employment/printarea/print_allannonces'
    var select=document.getElementById('annonce');

    $('<option  />').html('').appendTo(select);

    jQuery.ajax({
        url:link,
        beforeSend: function() {
        loader_div(select,'annonce');
    },
    complete: function(){
        remove_loader_div('annonce');
    },
        dataType: 'json',
        type: 'get',
        success: function(data) {
            data.forEach(function(item,k) {
            $('<option value="'+item['id']+'"/>').html(item['number']+' / '+item['year']).appendTo(select);
            });
            view_noty("success","تم تحميل الاعلانات");
            },
        error: function (e,xhr,opt) {
            showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });
  }
function loadfromapi(){
    var annonce_select_id='annonce';
    var annonce_select=document.getElementById(annonce_select_id);
    annonceval=$('#'+annonce_select_id).val();
    var link=sitelink+'/api/upgrade/get_statics?annonce_id='+annonceval;
    jQuery.ajax({
        url:link,
        dataType: 'json',
        type: 'get',
        beforeSend: function() {
        loader_div(annonce_select,'annonce');
    },
    complete: function(){
        remove_loader_div('annonce');
    },
        success: function(data) {
            if(data['result'] === 'error'){
                view_noty('success',"خطأ فى تحميل البيانات");
                return ;
            }
            load_data_html(data['result']);
            view_noty('success',"تم تحميل الاحصائيات");
            },
        error: function (e,xhr,opt) {
            showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });
    
}
function load_data_html(data){
    const object1=data;
    var html='';
    html+='<table class="table table-bordered table-sm">';
        html+='<thead class="thead-light">';
            html+='<tr class="text-center">';
                html+='<th scope="col" rowspan="2" class="table-primary p-4">';
                    html+='كود';
                html+='</th>';
                html+='<th scope="col" rowspan="2" class="p-4">';
                    html+='اسم الوظيفة';
                html+='</th>';
                html+='<th scope="col" colspan=3>';
                    html+='التقديم';
                html+='</th>';
                html+='<th scope="col" colspan=4>';
                    html+='التظلمات';
                html+='</th>';
                html+='<th scope="col" colspan=4>';
                    html+='الاختبارات التحريرية';
                html+='</th>';
            html+='</tr>';
            html+='<tr>';
            html+='<th scope="col">';
                html+='اجمالى';
            html+='</th>';
            html+='<th scope="col">';
                html+='مقبول';
            html+='</th>';
            html+='<th scope="col">';
                html+='غير مقبول';
            html+='</th>';
            
            html+='<th scope="col">';
                html+='محول للتظلمات';
            html+='</th>';
            html+='<th scope="col">';
                html+='تقدم للتظلمات';
            html+='</th>';
            html+='<th scope="col">';
                html+='مقبول';
            html+='</th>';
            html+='<th scope="col">';
                html+='مرفوض';
            html+='</th>';
            
            html+='<th scope="col">';
                html+='محول للاختبارات';
            html+='</th>';
            html+='<th scope="col">';
                html+='تقدم للاختبارات';
            html+='</th>';
            html+='<th scope="col">';
                html+='غير مقبول';
            html+='</th>';
            html+='<th scope="col">';
                html+='مقبول';
            html+='</th>';
        html+='</tr>';
        html+='</thead>';
        
    for (const [key, value] of Object.entries(object1)) {
        info=value['info'];
        convertoinput=value['convertoinput'];
        accepted=value['accepted']['count'];
        notaccepted=value['notaccepted']['count'];
        all=accepted + notaccepted;
        applied_count=value['applied']['count'];
        applied_banned=value['applied']['notaccepted']['count'];
        applied_accepted=value['applied']['accepted']['count'];
        makft=all-applied_accepted;
        tahriry=value['tahriry'];
        if(makft < accepted){
            html+='<tr class="bg-danger">';
        }else{
            html+='<tr>';
        }
        if(makft < accepted){
            html+='<th scope="row" class="bg-primary">';
        }else{
            html+='<th scope="row" class="table-primary">';
        }
                html+=info['code'];
            html+='</td>';
            html+='<th>';
                html+=info['name']+' : '+info['job_name'];
            html+='</td>';
            // كل اللى اتقدم
            if(makft < accepted){
                html+='<td class="bg-danger">';
            }else{
                html+='<td class="table-danger">';
            }
                html+=all;
            html+='</td>';
            //مقبول
            if(makft < accepted){
                html+='<td class="bg-danger">';
            }else{
                html+='<td class="table-success">';
            }
                html+=accepted;
            html+='</td>';
            //غير مقبول
            html+='<td>';
                html+=notaccepted;
            html+='</td>';
            //محول للتظلمات
            html+='<td>';
                html+=notaccepted;
            html+='</td>';
            //قدم تظلمات
            html+='<td>';
                html+=applied_count;
            html+='</td>';
            //مقبول تظلمات
            html+='<td>';
                html+=applied_accepted;
            html+='</td>';
            //مرفوض تظلمات
            html+='<td class="table-success">';
                html+=applied_banned;
            html+='</td>';
            html+='<td>';
                html+=tahriry['count'];
            html+='</td>';
            
        html+='</tr>';
      }
      html+='<table>';
      $('.demo').html(html);
}