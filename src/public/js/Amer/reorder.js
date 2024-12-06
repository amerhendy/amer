(function(){
    
    getKeyByValue=(Obj, value )=>{
        results=new Array();
        for( var prop in Obj ) {
            if( Obj.hasOwnProperty( prop ) ) {
                 if( Obj[ prop ] === value )
                    results.push(prop);
                     //return prop;
            }
        }
        return results;
    }
    if(window.Amer.ReOrder.isRtl) {
        $( " <style> .ui-sortable ol {margin: 0;padding: 0;padding-right: 30px;}ol.sortable, ol.sortable ol {margin: 0 25px 0 0;padding: 0;list-style-type: none;}.ui-sortable dd {margin: 0;padding: 0 1.5em 0 0;}</style>" ).appendTo( "head" )
    }
    setnestedSortable=()=>{
        var list={
            listType:'ul',
            forcePlaceholderSize: true,
            handle: 'div',
            helper: 'clone',
            items: 'li',
            opacity: .6,
            placeholder: 'placeholder',
            revert: 250,
            tabSize: 25,
            rtl: window.Amer.ReOrder.isRtl,
            tolerance: 'pointer',
            toleranceElement: '> div',
            maxLevels:window.Amer.ReOrder.max_level,
            isTree: true,
            expandOnHover: 700,
            startCollapsed: true,
            uuid:true,
        };
        $( "#easymm" ).nestedSortable(list);
    };
    createlistdiv=()=>{
        $('#'+window.Amer.ReOrder.MainId).append($('<div>'+jstrans.actions.reorder_text+'</div>').addClass(window.Amer.ReOrder.getReorderContentClass));
    };
    createStylediv=()=>{
        $('#'+window.Amer.ReOrder.MainId).append($(`<div></div>`).attr('style','min-height:250px;max-height:250px;overflow:auto').attr('id','MasterUl'));
    };
    createbtndiv=()=>{
        var div=$(`<div></div>`).addClass('mt-3');
        var btnI=$(`<button></button>`).attr('id','toArray').addClass('btn btn-success text-light').attr('data-style','zoom-in').html(`<i class="fa fa-save"></i>`)
        var btnII=$(`<a></a>`).attr('href',window.Amer.ReOrder.previousUrl).addClass('btn btn-secondary text-decoration-none').html('<span class="fa fa-ban"></span>')
        $(div).append(btnI);
        $(div).append(btnII);
        $('#'+window.Amer.ReOrder.MainId).append(div);
    };
    createMasterUl=()=>{
        //$('#MasterUl').append($('<ul></ul>').addClass('p-0 ui-sortable').attr('id','easymm'));
        $('#MasterUl').append($('<ul></ul>').addClass('p-0').attr('id','easymm'));
    };
    createRooteLi=(rootDiv=null,rootData=null)=>{
        var entry=window.Amer.ReOrder.entry;
        var idParents=window.Amer.ReOrder.idParents;
        if(rootDiv == null){
            rootDiv=$('#easymm');
        }
        
        if(rootData == null){
            var rootData=new Array();
            $.each(idParents,function(k,v){
                if(v !== null){return;}
                rootData.push(entry[k])
            });
        }
        $.each(rootData,function(k,v){
            var label=v[window.Amer.ReOrder.label];
            var id=v[window.Amer.ReOrder.key];
            var icon=v.icon;
            var link=v.link;
            var li=$('<li></li>').attr('id','menu-'+id).addClass('lisortable').data('id',id).data('parent_id',v.parent_id);
            var nsrow=$('<div></div>').addClass('ns-row row border rounded text-dark bgWhite');
            var nstitle=$('<div></div>').addClass('col-sm-5').html(`<i class="${icon}"></i> ${label} `).attr('id','fortitle-'+id);
            var nsurl=$('<div></div>').addClass('col-sm-5').html(`${v.link} `);
            var nsactions=$('<div></div>').addClass('actions col-sm').html(`
                <a href="#"
								class="btn btn-icon btn-active-light-primary w-10px h-10px m-2 me-5 edit-menu"
								title="Edit"><i class="bi bi-pencil fs-4"></i></a><a href="#"
								class="btn btn-icon btn-active-light-danger w-10px h-10px m-2 delete-menu"
								title="Delete"><i class="bi bi-trash fs-4"></i></a><input type="hidden" name="menu_id"
								value="1">
                                `);
            $(li).append(nsrow);
            $(nsrow).append(nstitle);
            $(nsrow).append(nsurl);
            //$(nsrow).append(nsactions);
            $(rootDiv).append(li);
            //get from entry where prent id = v.id
            var childs=new Array();
            if(Object.values(idParents).includes(id)){
                //getkeys
                $.each(getKeyByValue(idParents,id),function(l,m){
                    
                    childs.push(entry[m]);
                });
                //
                $('<span class="ms-3 fs-7 fa-angle-down fas" title="Click to show/hide children"><span></span></span>').appendTo($('#fortitle-'+id))
                
                var newUl=$('<ul></ul>').attr('id','ulMenu-'+id).data('forId',id)
                //.addClass('bg-transparent')
                .appendTo($('#menu-'+id))
                createRooteLi($('#ulMenu-'+id),childs);
                //set lis
            }
            
        });
    };
    $('#'+window.Amer.ReOrder.MainId).html('');
    createlistdiv();
    createStylediv();
    createbtndiv();
    createMasterUl();
    createRooteLi();
    setnestedSortable();
    $('#toArray').click(function(e){
        var arraied = $('#easymm').nestedSortable('toArray', {startDepthCount: 0, expression: /([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})/ });
        $.ajax({
            url: window.Amer.ReOrder.RequestPath,
            type: 'POST',
            data: { tree: JSON.stringify(arraied) },
            dataType:'json',
            contentType:"application/x-www-form-urlencoded; charset=UTF-8",
            crossDomain:true,
            'Accept':'application/json, text/javascript, */*; q=0.01',
        })
        .done(function() {
            new Noty({
                type: "success",
                text: `<strong>${jstrans.actions.reorder_success_title}</strong><br>${jstrans.actions.reorder_success_message}`
            }).show();
        })
        .fail(function() {
            new Noty({
                type: "error",
                text: `<strong>${jstrans.actions.reorder_error_title}</strong><br>${jstrans.actions.reorder_error_message}`
            }).show();
        })
        .always(function() {
            console.log("complete");
        });
    });
    
})(jQuery);