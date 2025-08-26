(function(){
    createFields=(uniqueid)=>{
        var fields=['type','target','link','page_id'];
        $.each(fields,function(k,v){
            fn=`createField${v}`;
            window[fn](uniqueid);
        });
    };
    createFieldtype=(uniqueid)=>{
        var typeuniqueid=generateUUID().split('-')[0];
        var mainDiv=$(`[uniqueid=${uniqueid}]`);
        var div=$(`<div class="col-sm-12 page_or_link_select"></div>`);
        var titleDiv=$(`<div  class="form-label"><label for="page_or_link_select" class="form-label">${jstrans['Menu']['linkType']}</label></div>`);
        var select=$('<select></select>');
        $(select).addClass('form-control')
        $(select).attr('data-identifier','page_or_link_select');
        $(select).attr('name','type');
        $(select).attr('uniqueid',typeuniqueid);
        $(select).attr('onchange','setTypeSelectResult(this);');
        $.each(typesTypes,function(k,v){
            var opt=$('<option></option>')
            $(opt).attr('value',k);
            $(opt).html(v);
            if(MenuEntry.type == k){
                $(opt).attr('selected','selected');
            }
            $(select).append($(opt));
            
        });
        $(titleDiv).appendTo($(div));
        $(select).appendTo($(div));
        $(div).appendTo($(mainDiv));
    };
    createFieldtarget   =(uniqueid)=>{
        var typeuniqueid=generateUUID().split('-')[0];
        var mainDiv=$(`[uniqueid=${uniqueid}]`);
        var div=$(`<div class="col-sm-12 page_or_link_target"></div>`);
        var titleDiv=$(`<div  class="form-label"><label for="page_or_link_target" class="form-label">${jstrans['Menu']['target']}</label></div>`);
        var select=$('<select></select>');
        $(select).addClass('form-control')
        $(select).attr('data-identifier','page_or_link_target');
        $(select).attr('name','target');
        $(select).attr('uniqueid',typeuniqueid);
        //$(select).attr('onchange','setTypeSelectResult();');
        $.each(typesTarget,function(k,v){
            var opt=$('<option></option>')
            $(opt).attr('value',k);
            $(opt).html(v);
            if(MenuEntry.target == k){
                $(opt).attr('selected','selected');
            }
            $(select).append($(opt));
        });
        $(titleDiv).appendTo($(div));
        $(select).appendTo($(div));
        $(div).appendTo($(mainDiv));
    };
    createFieldlink     =(uniqueid)=>{
        var fieldValue;
        var typeuniqueid=generateUUID().split('-')[0];
        var mainDiv=$(`[uniqueid=${uniqueid}]`);
        var div=$(`<div class="col-sm-12 page_or_link_link"></div>`);
        var titleDiv=$(`<div  class="form-label"><label for="page_or_link_link" class="form-label">${jstrans['Menu']['link']}</label></div>`);
        var input=$('<input type="text">');
        $(input).addClass('form-control')
        $(input).attr('data-identifier','page_or_link_link');
        $(input).attr('name','link');
        $(input).attr('uniqueid',typeuniqueid);
        var helptext=`<small class="form-text text-muted"></small>`;
        $(titleDiv).appendTo($(div));
        $(input).appendTo($(div));
        $(helptext).appendTo($(div));
        $(div).appendTo($(mainDiv));

    };
    createFieldpage_id  =(uniqueid)=>{
        var typeuniqueid=generateUUID().split('-')[0];
        var mainDiv=$(`[uniqueid=${uniqueid}]`);
        var div=$(`<div class="col-sm-12 page_or_link_link_select"></div>`);
        var titleDiv=$(`<div  class="form-label"><label for="page_or_link_link" class="form-label">${jstrans['Menu']['page_link']}</label></div>`);
        var select=$('<select></select>');
        $(select).addClass('form-control')
        $(select).attr('data-identifier','page_or_link_link');
        $(select).attr('name','link');
        $(select).attr('uniqueid',typeuniqueid);
        //$(select).attr('onchange','setTypeSelectResult();');
        $.each(typesactive_pages,function(k,v){
            var opt=$('<option></option>')
            $(opt).attr('value',v.id);
            $(opt).html(v.name);
            if(typesactive_pages.id == v.id){
                $(opt).attr('selected','selected');
            }
            $(select).append($(opt));
        });
        $(titleDiv).appendTo($(div));
        $(select).appendTo($(div));
        $(div).appendTo($(mainDiv));
    };
    setTypeSelectResult =(e)=>{
        $("select[data-identifier*='page_or_link'] ,input[data-identifier*='page_or_link']").not('select[data-identifier=page_or_link_select]').attr('disabled', 'disabled');
        $("select[data-identifier='page_or_link_target']").removeAttr('disabled');
        console.log($(e).val());
        
        switch($(e).val()) {
            case 'external_link':
                $('.page_or_link_link').show();
                $('.page_or_link_link_select').hide();
                $("input[data-identifier='page_or_link_link']").removeAttr('disabled');
                $("input[data-identifier='page_or_link_link']").attr('dir','ltr');
                $($("input[data-identifier='page_or_link_link']").parent().find('label')).html(jstrans.Menu.external_link_placeholder)
                $($("input[data-identifier='page_or_link_link']").parent().find('small')).html('https://www.dom.com/other?query')
                $("input[data-identifier='page_or_link_link']").val(MenuEntry.link)
                break;
            case 'internal_link':
                $('.page_or_link_link').show();
                $('.page_or_link_link_select').hide();
                $("input[data-identifier='page_or_link_link']").removeAttr('disabled');
                $($("input[data-identifier='page_or_link_link']").parent().find('label')).html(jstrans.Menu.internal_link_placeholder)
                $($("input[data-identifier='page_or_link_link']").parent().find('small')).html(internal_linkHelper)
                $("input[data-identifier='page_or_link_link']").val(MenuEntry.link)
                
                break;
            case 'page_link':
                $('.page_or_link_link').hide();
                $('.page_or_link_link_select').show();
                $("select[data-identifier='page_or_link_link']").removeAttr('disabled');
                $("input[data-identifier='page_or_link_link']").val(MenuEntry.link)
                break;
            default:
                
        };
    };
    bpFieldInitPageOrLinkElement=(element)=>{   
        uniqueid=generateUUID().split('-')[0];
        $(element).attr('uniqueid',uniqueid);
        createFields(uniqueid);
        //$("[class*='page_or_link']").hide();
        $("select[data-identifier*='page_or_link'] ,input[data-identifier*='page_or_link']").attr('disabled', 'disabled');
        $("select[data-identifier=page_or_link_select]").removeAttr('disabled');
        setTypeSelectResult($('[data-identifier=page_or_link_select]'));
    };
    $("[name=protocol]").select2({tags: true});
    $("[name=target]").select2({tags: true});
    $("[data-identifier=page_or_link_select]").select2();
    $("[name=page_id]").select2();
})(jQuery);
