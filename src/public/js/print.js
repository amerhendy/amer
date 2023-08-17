$('form').ready(function(){
    removeAllOptions(document.getElementById('annonce'));
    removeAllOptions(document.getElementById('stage'));
    removeAllOptions(document.getElementById('job'));
    removeAllOptions(document.getElementById('start'));
    removeAllOptions(document.getElementById('end'));
    set_ann_name();
    $('#annonce').change(function(){
        removeAllOptions(document.getElementById('stage'));
        removeAllOptions(document.getElementById('job'));
        removeAllOptions(document.getElementById('start'));
        removeAllOptions(document.getElementById('end'));
        $('#actionareatitle').show();
        $('#actionarea').show();
        $('#typestitle').show();
        $('#types').show();
        $('#show').show();
        set_stages();
        set_job();
        set_start();
        //set start and end
    });
    $('#stage').change(function(){
        removeAllOptions(document.getElementById('start'));
        removeAllOptions(document.getElementById('end'));
        set_start();
    });
    $('#job').change(function(){
        removeAllOptions(document.getElementById('start'));
        removeAllOptions(document.getElementById('end'));
        set_start();}
    );
    $('#accept').change(function(){
        removeAllOptions(document.getElementById('start'));
        removeAllOptions(document.getElementById('end'));
        set_start();}
    );
    $('#show').click(function(){
        gotoprint();
    });
});
function set_ann_name(){
    var link=websitelink+'/api/admin/employment/printarea/print_allannonces'
    var select=document.getElementById('annonce');
    $('<option  />').html('').appendTo(select);
    jQuery.ajax({
        url:link,
        dataType: 'json',
        type: 'get',
        success: function(data) {
            data.forEach(function(item,k) {
            $('<option value="'+item['id']+'"/>').html(item['number']+' / '+item['year']).appendTo(select);
            });
            },
        error: function (e,xhr,opt) {
            showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });
  }
function set_stages(){
    var link=websitelink+'/api/admin/stages'
    var select=document.getElementById('stage');
    $('<option  />').html('').appendTo(select);
    $('<option value="0"/>').html("ايقاف").appendTo(select);
    jQuery.ajax({
        url:link,
        dataType: 'json',
        type: 'get',
        success: function(data) {
            data.forEach(function(item,k) {
            $('<option value="'+item['id']+'"/>').html(item['name']).appendTo(select);
            });
            $('<option value="0"/>').html("").appendTo(select);
            },
        error: function (e,xhr,opt) {
            showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });

}
function set_job(){
    annonceval=$('#annonce').val();
    var link=websitelink+'/api/admin/employment/printarea/print_job_by_annonce/'+annonceval
    var job_select=document.getElementById('job');
    removeAllOptions(document.getElementById('job'));
    removeAllOptions(document.getElementById('start'));
    $('<option  />').html('').appendTo(job_select);
    if(annonceval === ''){
        $('#show').hide();
        $('#print').hide();
        $('#download').hide();
        return '';
      }
      jQuery.ajax({
        url:link,
        dataType: 'json',
        type: 'get',
        success: function(data) {
            data.forEach(function(item,index){
                $('<option value="'+item['id']+'"/>').html(item['code'] +'-'+item['job_name']).appendTo(job_select);
            });

            },
        error: function (e,xhr,opt) {
            showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            console.log(opt);
        }
    });

  }
  
function set_start(){
var annonce=$('#annonce').val();
var stage=$('#stage').val();if(stage === ''){stage='all';}
var job=$('#job').val();if(job === ''){job='all';}
var accept=$('#accept').val();
var select=document.getElementById('start');
var link=websitelink+'/api/admin/employment/printarea/print_allpersons_id/'+annonce+'/'+stage+'/'+job+'/'+accept;
jQuery.ajax({
    url:link,
    dataType: 'json',
    type: 'get',
    success: function(data) {
        document.getElementById('length').textContent=data.length;
        for(x in data){
            $('<option value="'+data[x]['id']+'"/>').html(data[x]['id']).appendTo(select);
        }
        set_end(data);
        },
    error: function (e,xhr,opt) {
        showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
    }
});
}
  function set_end(data){
        var select=document.getElementById('end');
            for(x in data){
                $('<option value="'+data[x]['id']+'"/>').html(data[x]['id']).appendTo(select);
            }
}
function get_start_end(){
        var start=$("select[name=start]");
        var start_options=document.getElementById("start").options;
        var start_seleccted=start.find(':selected').val();

        var end=$("select[name=end]");
        var end_options=document.getElementById("end").options;
        var end_seleccted=end.find(':selected').val();

        var st=new Array();
        var ed=new Array();
        
        for(x in start_options){
            if(document.getElementById("start").options[x].value == start_seleccted){
                var a=x;
            }
            if(document.getElementById("end").options[x].value == end_seleccted){
                var b=x;
            }
            st.push(x);
        }
        var start_seleccted_x=st.indexOf(a);
        var end_seleccted_x=st.indexOf(b);
        var faarq=(end_seleccted_x - start_seleccted_x)+1;
        var asd=st.splice(start_seleccted_x,faarq);
        var ed=Array();
        for(x in asd){
            ed.push(start_options[asd[x]].value);
        }
        return ed;
    }
////////////////
function gotoprint(){
    var actions=$("select[name=actions]");
    var actions_options=document.getElementById("actions").options;
    var actions_seleccted=actions.find(':selected').val();
    var types=$("select[name=types]");
    var types_options=document.getElementById("types").options;
    var types_seleccted=types.find(':selected').val();
    var ids=get_start_end();
    if(ids.length == 0){ids.push(0);}
    var link=websitelink+'/admin/printarea/api/'+actions_seleccted+'/'+types_seleccted+'/'+ids+'';
    window.open(link);
}
