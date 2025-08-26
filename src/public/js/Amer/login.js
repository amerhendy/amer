(function(){
    $('#loginformshow').on('click',function(){window.loginformshow()})    
    loginformshow=function(){
        loginform=$("<div></div>");
        formarea=$('<div></div>');
        linkarea=$('<div></div>')
        temp=$('template[id^=login-template]').clone();
        $.each(temp,function(k,v){
            cont=$(v)[0].content;
            btn=$(cont).find('[data-bs-target]');
            $(linkarea).append($(btn));
            $(formarea).append($(v).html());
        })
        $(loginform).append($(linkarea))
        $(loginform).append($(formarea))
        $.each($(formarea).children(),function(k,v){
            //console.log($(v));
        });
        //console.log(linkarea);
        Swal.fire({
            title:LOGINTITLE,
            html:loginform,
            showCancelButton:false,
            showConfirmButton:false,
        })
        $.each($("[data-bs-target^=login]"),function(k,v){
            v.addEventListener('click',function(){window.chooseclass(this)})    
        });
        $.each($(".loginbtn"),function(k,v){
            v.addEventListener('click',function(){window.preConfirm(this)})    
        });
        document.getElementById('nid'),addEventListener('input',function(){window.checknid()})
        document.getElementById('uid'),addEventListener('input',function(){window.checkuid()})
    }
    chooseclass=function(e) {
        $.each($('[data-bs-target^=login]'),function(k,v){
            $(v).removeClass('btn-primary')
            $(v).removeClass('btn-success')
            var target=$(v).attr('data-bs-target');
            $('#'+target).css('display','none');
        });
        $(e).addClass('btn-success');
        var target=$(e).attr('data-bs-target');
        $('#'+target).css('display','block');
    }
    checknid=function (){
        let nidtext=document.getElementById('nid').value;
        if(nidtext.length < 14){
            $('#nid').addClass("is-invalid");
        }else if(nidtext.length > 14){
            $('#nid').addClass("is-invalid");
        }else{
            $('#nid').removeClass("is-invalid");
        }
    }
    checkuid=function (){
        let uidtext=document.getElementById('uid').value;
        if(uidtext.length !== 5){
            $('#uid').addClass("is-invalid");
        }else{
            $('#uid').removeClass("is-invalid");
        }
    }
    preConfirm=function (e){
        var maindiv=$(e).parent();
        var maindiv_id=$(maindiv).attr('id');
        var link=$(e).attr('data-bs-link');
        var seria=$('#'+maindiv_id+' :input').serializeArray();
        seria[seria.length] = { name: "location", value: window.Amer.locationData};
        var data=new Array();
        $.each(seria,function(k,v){
            var obj={};
            obj[v['name']]=v['value'];
            data.push(obj);
        })
        $.ajax({
            url:link,
            type:'POST',
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType:'application/x-www-form-urlencoded',
                dataType:"json",
            data:seria,
            success:function(data){
                if(data['errors'])
                {
                    $('.swal2-validation-message').css('display','flex');
                    if(data['errors']['nid']){
                        return $('.swal2-validation-message').html(data['errors']['nid']);
                        }
                        if(data['errors']['uid']){
                        return $('.swal2-validation-message').html(data['errors']['uid']);
                        }
                }else{
                    location.reload();
                }
            },
            error: function(e, xhr, opt) {
                alert("error", "Error requesting " + opt + ": " + xhr.status + " " + xhr.responseText);
            }
        });
    }
})(jQuery);
