Noty.overrideDefaults({layout   : 'center',theme    : 'backstrap',timeout  : 2500,closeWith: ['click', 'button'],});

const showbtn=document.getElementById('show');
  showbtn.addEventListener('click', function() {
    const file=document.getElementById('file-selector').files[0];
    readImage(file);
    
  });
  
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
    console.log(res);
    var trs=res.split("\n");
    if(filetype === 'application/vnd.ms-excel'){
        var theadoption=trs[0].split(";");
        }
        if(filetype === 'text/csv'){
            var theadoption=trs[0].split(",");
        }
    newform='';
    
    thead='<tr class="border">';
    thead+='<th class="border text-center" data-class-name="priority">الرقم التعريفى</th>';
    thead+='<th class="border text-center" data-class-name="priority">الرقم القومى</th>';
    thead+='<th class="border text-center" data-class-name="priority">الاسم</th>';
    thead+='<th class="border text-center" data-class-name="priority">الاعلان</th>';
    thead+='<th class="border text-center" data-class-name="priority">رقم الوظيفة</th>';
    thead+='<th class="border text-center" data-class-name="priority">الوظيفة</th>';
    thead+='<th class="border text-center" data-class-name="priority">المدينة</th>';
    thead+="</tr>";
    $('thead').html(thead);
    $('tfoot').html(thead);
    html='';
    dd=trs;
    $totol=dd.length;
    for(var x=1,ln=dd.length; x<ln; x++){
        setTimeout(function(y){
        var olg=dd[y];
        tr=olg.split(";");
        dp(tr[0],tr[1]);
        if(y === x-1){
            dotable();
        }
        },x*3000,x);
    }
    
}
function dotable(){
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
}
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
function dp(nid,degree){
    //$('#DOES thead tr').append('<td id=amer>lol</td>')
    var sendlink=sitelink+'/api/upgrade/get_uid_bynid_csv?nid='+nid;
    jQuery.ajax({
        url:sendlink,
        dataType: 'json',
        type: 'get',
beforeSend: function() {
        loader_div('select','stages');
    },
    complete: function(){
        remove_loader_div('stages');
    },
        success: function(data) {
            if(data['result'] === 'error'){return;}
            html='';
            html+='<tr class="border">';
            json=data['result'];
                html+='<td>'+json['id']+'</td>';
                html+='<td>'+nid+'</td>';
                html+='<td>'+json['fname']+' '+json['sname']+' '+json['tname']+' '+json['lname']+'</td>';
                html+='<td>'+json['employment_startannonces']['number']+'/'+json['employment_startannonces']['year']+'</td>';
                html+='<td>'+json['employment_job']['id']+'</td>';
                html+='<td>'+json['employment_job']['code']+' :: '+json['employment_job']['job_name']+'</td>';
                html+='<td>'+degree+'</td>';
                html+='<td>'+json['city']+'</td>';
        html+='</tr>';
        $('tbody').append(html);
            /*
            data.forEach(function(item,k) {
            $('<option value="'+item['id']+'"/>').html(item['name']).appendTo(select);
            $('<option value="'+item['id']+'"/>').html(item['name']).appendTo(newstage);
            });*/
            view_noty("success","تم تحميل المراحل");
            },
        error: function (e,xhr,opt) {
            showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });
}