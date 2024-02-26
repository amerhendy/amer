$(document).ajaxStart(function() { Pace.restart(); });
$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
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
$( document ).ready(function(){
    remove_all_options_ids(Array('annonce','stage','job','accept','start','end'));
    css_hide_ids(Array('DOES','show'));
    set_ann_name();
    set_stages();
});
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
            set_accept_selects();
            set_start();
        },
        error: function (e,xhr,opt) {
                showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
                console.log(opt);
            }
        });
    }
    function set_accept_selects(){
        var select=document.getElementById('accept');
        $('<option>').html("").appendTo(select);
        $('<option value="1">').html("{{trans('trojan.accepted')}}").appendTo(select);
        $('<option value="2">').html("{{trans('trojan.notaccepted')}}").appendTo(select);
    }
    function set_stages(){
        var link=sitelink+'/api/admin/stages'
        var select=document.getElementById('stage');
        var newstage=document.getElementById('new_stage');
        $('<option value="0"/>').html("ايقاف").appendTo(select);
        $('<option  />').html('').appendTo(newstage);
        $('<option value="0"/>').html("ايقاف").appendTo(newstage);
        jQuery.ajax({
            url:link,
            dataType: 'json',
            type: 'get',
    beforeSend: function() {
            loader_div(select,'stages');
        },
        complete: function(){
            remove_loader_div('stages');
        },
            success: function(data) {
                data.forEach(function(item,k) {
                $('<option value="'+item['id']+'"/>').html(item['name']).appendTo(select);
                $('<option value="'+item['id']+'"/>').html(item['name']).appendTo(newstage);
                });
                view_noty("success","تم تحميل المراحل");
                },
            error: function (e,xhr,opt) {
                showerror("Error","Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
                console.log(opt);
            }
        });
    }
    function set_start(){
        var annonce_select_id='annonce';
        var annonce=$('#'+annonce_select_id).val();
        if(annonce === ''){return;}
        var stage=$('#stage').val();
        var job_select_id='job';
        var accept_select_id='accept';
        var start_select_id='start';
        var show_btn_id='show';
        var length_span_id='length';

        if(annonce === ''){annonce=0;}
        if(stage === '' || stage === null){stage='all';}
        var job=$('#'+job_select_id).val();if(job === '' || job === null){job='all';}
        var accept=$('#'+accept_select_id).val();
        if(accept === '' || accept === null){accept=0;}
        var select=document.getElementById(start_select_id);
        var link=sitelink+'/api/upgrade/recorded_in_stage/list?annonce_id='+annonce+'&stage='+stage+'&job='+job+'&accept='+accept;

        jQuery.ajax({
            url:link,
            dataType: 'json',
            type: 'get',
            beforeSend: function() {
                loader_div(select,'setstart');
            },
            complete: function(){
                remove_loader_div('setstart');
            },
            success: function(data) {

                document.getElementById(length_span_id).textContent=data.length;
                if(data.length > 5000){
                    $('#'+show_btn_id).css('display','none');
                }
                if(data.length === 0){
                    $('#'+show_btn_id).css('display','none');
                }
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

    function remove_all_options_ids(ids){
        ids.forEach(function(item){
            removeAllOptions(document.getElementById(item));
        });
    }
    function css_hide_ids(ids){
        ids.forEach(function(item){
            $('#'+item).css('display','none');
        });
    }
    function css_show_ids(ids){}
