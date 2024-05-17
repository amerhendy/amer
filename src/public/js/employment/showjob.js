(function() {
    const Templates=['annonceInfo','infoTemplate','printBtnTemplate','nidaskTemplate'];
    $.each(Templates,function(k,v){eval('var '+v + '='+ "document.getElementById('"+v+"');");});
    id = $('#showjobs');
    window.Amer.jobSlug=id.attr('jobid');
    window.Amer.annonceSlug = id.attr('annonceid');
    const getjob_link = api + 'employment/getjob';
    var data={jobSlug:window.Amer.jobSlug,annonceSlug:window.Amer.annonceSlug,page:"showJob"}
    jQuery.ajax({
        url: getjob_link,
        cache: false,
        dataType: 'json',
        data,
        contentType:'application/x-www-form-urlencoded',
        type: 'POST',
        beforeSend: function(){
            var  section=$('#jobInfoSection');
            $('style').append('#jobInfoSection{width: 100%;position: relative;}');
            $(section).html($(`<div class="loader"></div>`));
        },
        success: function(data) {
            if(!objkey_exists(data,'data')){log(data);return ;}
            data=data.data;
            var info=data['data'];
            var data=data[0];
            if(typeof data == 'string'){
                if(startwith(data,'Content-Type: application/pdf;')){
                    var  section=$('#jobInfoSection');
                    var file= new Blob([data],{type:'application/pdf'});
                    var st=data.split(';\r\n');
                    var st=st[2].split('\r\n\r\n')
                    var iframe= document.createElement('iframe');
                    $(iframe).attr('style','top:0; left:0; bottom:0; right:0; width:100%; height:30cm; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;');
                    section.html(iframe);
                    iframe.src="data:application/pdf;base64,"+st[1]
                }
            }
            window.set_job(info);
        }
    });
    set_job=function (data) {
        window.createfooter(data);
    
    }
    setCols=function(data){
        var col=$('<div class="col"></div>')
        $.each(data,function(k,v){
            $(col).append(v);
        })
        return col;
    }
    createfooter=function(data){
        //console.log(data.Stage[1]);
        //var annonce=data.Employment_StartAnnonces;
        var Stage=data['Stage'];
            //create footer
        document.getElementById('nidannoncestage').value=Stage['2'];
        var footersection=$('section[id=showJob-footer]');
        var printTemp=$(printBtnTemplate).html();
        var printTemp=$(printTemp).clone();
        $(footersection).append(printTemp);
        var askForNid=parseInt(Stage[1]);
        if(askForNid == 0){
            var template=$(nidaskTemplate).html();
            var templateA=$(template).clone();
            templateA.text(Stage[0]);
            templateA.attr('front',Stage[1]);
            templateA.attr('code',Stage[2]);
            $(footersection).append(templateA);
            document.getElementById('app_pro').addEventListener('click',function(){window.stage()})
        }else if(askForNid === 1){
            form=$(footersection).closest('form');
            $(form).append(`<input type="hidden" name="_token" value="`+$('meta[name="csrf-token"]').attr('content')+`">`);
            $(form).attr('method','post');
            button=$('<button type="button" id="gototstatic" class="btn btn-primary btn-lg"><i class="fa fa-eye" aria-hidden="true"></i>'+data.Stage[0]+'</button>');
            $(footersection).append($(button));
            document.getElementById('gototstatic').addEventListener('click',function(){window.gotostaticpages(this)})
        }
        }
    gotostaticpages=function(e){
        form=$(e).closest('form');
        var btnlink= websitelink + "employment_operation/stage/" + window.Amer.annonceSlug + "/" + window.Amer.jobSlug;
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
        var btnlink= websitelink + "employment_operation/stage/" + window.Amer.annonceSlug + "/" + window.Amer.jobSlug;
        var realnid = $('#nidreal');
        var apilink = api;
        var trans = jstrans;
        if (nid_lenght > 14) {
            realnid.html(`<i class="text-danger">${nid_lenght} - 14</i>`);btn.hide();$(form).attr('action','');
            return;
        }
        if (nid_lenght < 14) {
            $('#nid')[0].classList.remove('is-invalid');
            realnid.html(`<i class="text-warning">${nid_lenght} - 14</i>`);btn.hide();$(form).attr('action','');
            return;
        }
            realnid.html(`<i class="text-success">${nid_lenght} - 14</i>`);
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
            if (parseInt(res) !== parseInt(nid_val[13])) {
                $('#nid').addClass('is-invalid');
                realnid.html(`<i class="text-warning">${jstrans.apply.nid_phisical_error}</i>`);return;
            }
            var urlarr=['employment','checknid',window.Amer.annonceSlug,window.Amer.jobSlug];
            var serial=form.serializeArray()
            var data={};
            $.each(serial,function(k,v){
                data[v.name]=v.value;
            });
            CheckingNid=check(window.Amer.jobSlug,window.Amer.annonceSlug,'showjobs',nid_val,data['page']);
    }
    insertlinkform=function(){}
    setCard=function(data,title,functionName=null,footer=null){
        if(data == '' || data == null || data == 'null'){return;}
        var template=$(infoTemplate).html();
        var templateA=$(template).clone();
        var cardHeader=$(templateA).find('.card-header');
        cardHeader.text(title);
        if(functionName !== null){
            $(templateA).attr('data-function',functionName);
            if (typeof window[functionName] === "function") {
                var center=window[functionName](data);
            }
        }
        var cardBody=$(templateA).find('.card-body')
        $(cardBody).html(center);
        if(footer !== null){
            $(cardBody).append(setCardFooter(footer))
        }
        return templateA;
        $(template).append(center);
    }
    setCardFooter=function(trs){
        var figcaption=$('<figcaption class="blockquote-footer"></figcaption>');
        figcaption.text(trs);
        return figcaption;
    }
    setExperiences=function(data){
    }
    setText=function(data){
        var div=$('<div class="card-text"></div>');
        var Code=$('<SPAN></SPAN>');
        Code.text(data);
        $(div).append(Code);
        return div;
    }
    
})(jQuery)
