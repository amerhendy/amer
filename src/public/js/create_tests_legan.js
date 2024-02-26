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
        $('.uploadarea').hide();
        removeAllOptions(document.getElementById('annonce'));
        set_ann_name();
        
    });
    $('#annonce').change(function(){
        loadcities();
        set_job();
        $('.uploadarea').show();
    });
    const showbtn=document.getElementById('show');
        showbtn.addEventListener('click', function() {
            const file=document.getElementById('file-selector').files[0];
            getselectewdinfo();
            //readImage(file);
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
function loadcities(){
    var annonce_select_id='annonce';
    var annonce_select=document.getElementById(annonce_select_id);
    annonceval=$('#'+annonce_select_id).val();
    var link=sitelink+'/api/employment/cities_by_annonce_id/'+annonceval;
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
            var kok=new Array();
            data['data'].forEach(function(item,key){
                id=item['id'];
                kok.push([item['id'],item['city_name']]);
                
            });
            load_sortablediv('selectcities','place_order_cities',kok)
            view_noty('success',"تم تحميل الاحصائيات");
            },
        error: function (e,xhr,opt) {
            showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });
}
function load_sortablediv(main,$fieldName,data){
    var valuejson=JSON.stringify(data);
    html='';
    var $allId = 'sao_all_'+Math.ceil(Math.random() * 1000000);
    var $selectedId = 'sao_selected_'+Math.ceil(Math.random() * 1000000);
    html+='<div class="row" data-init-function="bpFieldInitSelectAndOrderElement" data-all-options="'+valuejson+'" data-field-name="'+$fieldName+'">';
        html+='<div class="col-md-12">';
            html+='<ul id="'+$selectedId+'" data-identifier="drag-destination" class="'+$fieldName+'_connectedSortable select_and_order_selected float-left"></ul>';
            html+='<ul id="'+$allId+'"  data-identifier="drag-source" class="'+$fieldName+'_connectedSortable select_and_order_all float-right ui-sortable">';
            data.forEach(function(item,key){
                html+='<li value="'+item[0]+'"><i class="la la-arrows"></i> '+item[1]+'</li>';
            });
            
            html+='</ul>';
            html+='<div data-identifier="results">';
            html+='<select class="d-none"  name="'+$fieldName+'[]" data-selected-options="" multiple></select>';
            html+='</div>';
        html+='</div>';
    html+='</div>';
    $('#'+main).html(html);
    bpFieldInitSelectAndOrderElement($('div[data-field-name='+$fieldName+']'));
}
    function set_job(){
            var annonce_select_id='annonce';
        annonceval=$('#'+annonce_select_id).val();
        var link=sitelink+'/api/admin/employment/printarea/print_job_by_annonce/'+annonceval
        jQuery.ajax({
            url:link,
            dataType: 'json',
            type: 'get',
            beforeSend: function() {
            loader_div('job_select','setjob');
        },
        complete: function(){
            remove_loader_div('setjob');
        },
            success: function(data) {
                var kok=new Array();
                data.forEach(function(item,key){
                    id=item['id'];
                    job_name=item['code']+' :: '+ item['job_name'];
                    kok.push([item['id'],job_name]);
                    
                });
                load_sortablediv('selectjobs','place_order_jobs',kok)
                },
            error: function (e,xhr,opt) {
                showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
                console.log(opt);
            }
        });

    }
    function bpFieldInitSelectAndOrderElement(element) {
        var $dragSource = element.find('[data-identifier=drag-source]');
        var $dragDestination = element.find('[data-identifier=drag-destination]');
        var $hiddenSelect = element.find('[data-identifier=results] select');
        var $fieldName = element.attr('data-field-name');
        var $alreadySelectedOptions = $hiddenSelect.data('selected-options');
        var $allOptions = element.data('all-options');
        var $allId = 'sao_all_'+Math.ceil(Math.random() * 1000000);
        var $selectedId = 'sao_selected_'+Math.ceil(Math.random() * 1000000);
        element.find('[data-identifier=drag-destination]').attr('id', $selectedId);
        element.find('[data-identifier=drag-source]').attr('id', $allId);
        // initialize jQueryUI sortable
        $( "#"+$allId+", #"+$selectedId ).sortable({
            connectWith: "."+$fieldName+"_connectedSortable",
            create: function (event, ui) {
            },
            update: function() {
                var updatedlist = $(this).attr('id');

                if((updatedlist == $selectedId)) {
                    // clear all options inside the select
                    $hiddenSelect.html("");

                    // if there are no items dragged inside the selected area, abort
                    if($dragDestination.find('li').length=0) {
                        return;
                    }

                    // for each item dragged inside the selected area
                    // add a new selected option inside the hidden select
                    $dragDestination.find('li').each(function(val,obj) {
                        $hiddenSelect.append('<option value="'+obj.getAttribute('value')+'" selected></option>');
                    });
                }
            }
        }).disableSelection();
    }
    function readImage(file) {
        var filetype='';
        if (file.type && file.type.indexOf('text/csv') === 0) {
          var filetype='text/csv';
        }else if (file.type && file.type.indexOf('application/vnd.ms-excel') === 0) {
          var filetype='application/vnd.ms-excel';
        }else{
          console.log(file.type, file);
          return;
        }
        const reader = new FileReader();
        reader.addEventListener('load', (event) => {
              if (  $.fn.DataTable.isDataTable( '#DOES' ) ) {
                  table=$('#DOES').DataTable();
                  table.destroy();
              }
          table_data(event.target.result,filetype);
        });
        reader.readAsText(file,'UTF-8');
      }
      function table_data(res,filetype){
          var trs=res.split("\n");
          if(filetype === 'application/vnd.ms-excel'){
              var theadoption=trs[0].split(";");
              }
              if(filetype === 'text/csv'){
                  var theadoption=trs[0].split(",");
              }
          newform='';
          
          thead='<tr class="border">';
          for(i=0;i<theadoption.length-1;i++){
              var thname=theadoption[i];
              
              thead+='<th class="border text-center" data-class-name="priority">'+thname+'</th>';
          }
          $('#refareas').append(newform);
          thead+="</tr>";
          $('thead').html(thead);
          html='';
          for(i=1;i<trs.length-1;i++){
              tr_data=trs[i];
              tr_id=i;
              if(filetype === 'application/vnd.ms-excel'){
                  tr=tr_data.split(";");
              }
              if(filetype === 'text/csv'){
                  tr=tr_data.split(",");
              }
              html+='<tr class="border">';
              for(l=0;l<tr.length-1;l++){
                  get_userinfo(tr[0]);
                  html+='<td';
                  if(l === 0){
                      html+='  data-attr-id="table_th_id_'+tr[0]+'"';
                  }
                  html+='>'+tr[l]+'</td>';
              }
              html+='</tr>';
          }
          $('tbody').html(html);
          $('#DOES').DataTable({
              "info": true,
              'bDestroy':true,
              "ordering": true,
              searcing:false,
              paging:true,
              "pageLength": 100,
              "processing": true,
              autoWidth:true,
              "scrollY": 200,
              "scrollX": true,
              scrollCollapse:true,
              fixedColumns:   true,
              fixedHeader: true,
              select:true,
              dom: 'Bfrtip',
              buttons: [
                      'colvis',
                      'excel',
                      'print',
                      'copy',
                      'csv',
                      'pdf',
                      'selectAll',
                      'selectNone'
                  ],
                  select:{
                  style:'multi+shift',
              },
            initComplete: function () {
                  // Apply the search
                  this.api().columns().every( function () {
                      var that = this;
                  } );
              },
          });
          $('#update').css('display','block');
          $('#updatediv').css('display','block');
      }
      function getselectewdinfo(){
          $('div[data-init-function=bpFieldInitSelectAndOrderElement]').each(function(e){
            datafieldname=$(this).attr('data-field-name');
            pos=$('div[data-field-name='+datafieldname+']');
            console.log(pos.firstChild);
          });
      }