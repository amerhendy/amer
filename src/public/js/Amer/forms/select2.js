(function(){    
    select2Theme="bootstrap-5";
    var SELECT_ALL_LIMIT = 100;
    setSelect2CssStyle=function(){
        var style = $('style');
        var css=`select[readonly].select2-hidden-accessible + .select2-container {
                pointer-events: none;
                touch-action: none;
                }
                select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
                background: #eee;
                box-shadow: none;
                }
                select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow,
                select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
                display: none;
                }`;
        $(style)[0].append(css)
    }
    setPromiseSelect2=function(uniqueid){
        element=$('[uniqueid='+uniqueid+']');
        var $selectedOptions = typeof element.attr('data-selected-options') === 'string' ? JSON.parse(element.attr('data-selected-options')) : JSON.parse("[]");
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: element.attr('data-data-source'),
                dataType:'json',
                crossDomain:true,
                contentType:"application/x-www-form-urlencoded; charset=UTF-8",
                data: {
                    'keys': $selectedOptions
                },
                type: element.attr('data-method') ?? 'post',
                success: function (result) {
                    resolve(result);
                },
                error: function (result) {
                    console.log(result);
                    reject(result);
                }
            });
        });
    }
    setSelect2Info=function(uniqueid){
        setSelect2All();
        element=$('[uniqueid='+uniqueid+']');
        var info={};
        info['language']=window.Amer.Language;
        info['dir']=window.Amer.dir;
        info['theme']=window.Amer.bootstrap;
        info['placeholder']=window.Amer.forms[uniqueid].information.placeholder;
        info['minimumInputLength']=window.Amer.forms[uniqueid].information.minimumInputLength;
        info['dropdownParent']=window.Amer.forms[uniqueid].information.dropdownParent;
        info['disabled']=window.Amer.forms[uniqueid].information.disabled;
        info['multiple']=window.Amer.forms[uniqueid].information.multiple;
        info['allowClear']=window.Amer.forms[uniqueid].information.allowClear;
        info['templateResult']= formatRepo;
        info['templateSelection']=formatRepoSelection;
        info['dropdownAdapter']=$.fn.select2.amd.require('select2/selectAllAdapter');
        info['closeOnSelect']=true;
        info['scrollAfterSelect']=true;
        window.Amer.forms[uniqueid].select2=info;
    }
    registerSelect2WantedData=(uniqueid)=>{
        element=$('[uniqueid='+uniqueid+']');
        form=$(element).closest('form');
        info={};
        info['form'] = element.closest('form');
        info['placeholder'] = element.attr('data-placeholder') ?? '';
        info['minimumInputLength'] = element.attr('data-minimum-input-length') ?? 0;
        info['dataSource'] = element.attr('data-data-source') ?? false;
        info['method'] = element.attr('data-method') ?? false;
        info['fieldAttribute'] = element.attr('data-field-attribute') ?? false;
        info['datarelty'] = element.attr('data-rel-ty') ?? false;
        info['connectedEntityKeyName'] = element.attr('data-connected-entity-key-name') ?? false;
        info['includeAllFormFields'] = element.attr('data-include-all-form-fields')=='false' ? false : true;
        info['dropdownParent']=element.data('field-is-inline') ? $('#inline-create-dialog .modal-content') : document.body;
        info['allowClear'] = element.attr('data-column-nullable') == 'true' ? true : false;
        info['dependencies'] = JSON.parse(element.attr('data-dependencies') ?? false);
        info['ajaxDelay'] = element.attr('data-ajax-delay') ?? 500;
        info['selectedOptions'] = typeof element.attr('data-selected-options') === 'string' ? JSON.parse(element.attr('data-selected-options')) : JSON.parse("[]");
        info['isFieldInline'] = element.data('field-is-inline');
        info['arrayview']=element.data('arrayview') ?? false;
        info['multiple']=element.attr('multiple');
        info['sourceDataArray']=element.attr('data-array');
        //console.log(element.id(),element.attr('multiple'));
        
        if(info['multiple'] == 'multiple'){info['multiple']=true;}else{info['multiple']=false;}
        info['disabled']=element.data('read-only') ?? false;
        window.Amer.forms[uniqueid].information=info;
    }
    formatRepo=function(repo) {
        if (repo.loading) {
          return repo.text;
        }
        var markup = $("<div class='select2-result-repository__title' data-id='" + repo.id + "'>" + repo.text + "</div>");
        return markup;
    }
    formatRepoSelection=function(repo) {
        return repo.full_name || repo.text;
    }
    setSelect2BasicAjax=function(uniqueid){
        element=$('[uniqueid='+uniqueid+']');
        var info={};
        info['url']=element.attr('data-data-source');
        info['type']=element.attr('data-method')?? 'post';
        info['dataType']='json';
        info['crossDomain']=true;
        info['contentType']="application/x-www-form-urlencoded; charset=UTF-8";
        //info['formatResult']=formatState;
        //info['templateResult']=formatState;
        info['delay']=element.attr('data-ajax-delay');
        info['cache']=true;
        info['closeOnSelect']=true;
        if(element.attr('data-ajax-more')){
           var more= element.attr('data-ajax-more');
           $.each(JSON.parse(more),function(k,v){
            info[k]=v;
           });
        }
        window.Amer.forms[uniqueid].select2.ajax=info;
    }
    setSelect2All=function(){
        $.fn.select2.amd.define('select2/selectAllAdapter', [
            'select2/utils',
            'select2/dropdown',
            'select2/dropdown/attachBody'
            ], function(Utils, Dropdown, AttachBody) {
                function SelectAll() {}
                SelectAll.prototype.render = function(decorated) {
                    var $rendered = decorated.call(this);
                    var self = this;
                    var $selectAll = $('<button class="btn btn-xs btn-default btn-outline-secondary select_all" style="width:100%;margin-top: 5px;" type="button"><i class="fad fa-check-double"></i></button>');
                    var checkOptionsCount = function() {
                        var count = $('.select2-results__option').length;
                        $selectAll.html('<i class="fa fa-check-double"></i> (' + count + ') <i class="fa fa-check-double"></i>');
                        $selectAll.prop('disabled', count > SELECT_ALL_LIMIT);
                    }
                    var $container = $('.select2-container');
                    $container.bind('keyup click', checkOptionsCount);
                    var $dropdown = $rendered.find('.select2-dropdown')
                    $dropdown.prepend($selectAll);
                    $selectAll.on('click', function(e) {
                        var $results = $rendered.find('.select2-results__option[aria-selected=false]');
                        // Get all results that aren't selected
                        var dod=[];
                        $results.each(function() {
                            var $result = $(this);
                            var $div=$result.children();
                            var $id=$($div).data('id');
                            var $text=$($div).text();
                            dod.push({id:$id,text:$text});
                            
                        });
                        $.each(dod,function(k,v){
                            //self.trigger('select', {data: v}); // <-- TypeError, data is undefined
                        });
                        
                        self.trigger('close');
                    });
                    return $rendered;
                };
                return Utils.Decorate(
                    Utils.Decorate(
                        Dropdown,
                        AttachBody
                    ),
                    SelectAll
                );
            });
    }
    bpFieldInitSelect2FromAjax_ajax_data=(params,uniqueid)=>{
        var element=$('[uniqueid='+uniqueid+']');
        var form = element.closest('form');
        var attributes=window.Amer.forms[uniqueid].information.fieldAttribute ?? null;
        var $datarelty =window.Amer.forms[uniqueid].information.datarelty;
        var $depfordata=[];
        window.Amer.forms[uniqueid].information.dependencies.forEach((err)=>{
            console.log(form,err);
            
            var inpval=$(form.find('[id="'+err+'"], [name="'+err+'"], [name="'+err+'[]"]')).val();
            $depfordata.push({input:err,val:inpval,rel:$datarelty,attributes});
        });
        var info= {};
        info['q']=params.term;
        info['page']=params.page;
        info['dependencies']=$depfordata;
        if (window.Amer.forms[uniqueid].information.includeAllFormFields === true) {info['form']=form.serializeArray();}
        window.Amer.forms[uniqueid].select2.ajax.data=info;
        return window.Amer.forms[uniqueid].select2.ajax.data;
    };
    bpFieldInitSelect2FromAjax_ajax_processResults=(data, params,uniqueid)=>{
        $fieldAttribute=window.Amer.forms[uniqueid].information.fieldAttribute;
        params.page = params.page || 1;
        return {
            results: $.map(data['data'], function (item) {
                dod='';
                if(IsValidJSONString($fieldAttribute)){
                    var attArr=JSON.parse($fieldAttribute);
                    if(!Array.isArray(attArr)){
                        dod=item[attArr];
                    }else{
                        let obj={};
                        $.each(attArr,function(index, element){
                            vars=item[element];
                            if(window.Amer.forms[uniqueid].information.arrayview.length === 0){var divider='-';}else{
                                if(objkey_exists(window.Amer.forms[uniqueid].information.arrayview,'enum')){
                                    if(objkey_exists(window.Amer.forms[uniqueid].information.arrayview['enum'],element)){
                                        if(objkey_exists(window.Amer.forms[uniqueid].information.arrayview['enum'][element],vars)){
                                            vars=window.Amer.forms[uniqueid].information.arrayview['enum'][element][vars];
                                        }
                                    }
                                }
                            }
                            obj[element]=vars;
                        });
                        if(objkey_exists(window.Amer.forms[uniqueid].information.arrayview,'translate')){
                                dod=replacestr(window.Amer.forms[uniqueid].information.arrayview['translate'],obj);
                        }else{
                            dod=obj.join(divider);
                        }
                    }
                }
                return {
                    text: dod,
                    id: item[window.Amer.forms[uniqueid].information.connectedEntityKeyName]
                }
                /*
                return{
                    text:'sd',
                    id:'111'
                }
                return {
                    text: item[$fieldAttribute],
                    id: item[$connectedEntityKeyName]
                }
                */
            }),
            pagination: {
                 more: data.current_page < data.last_page
            }
        };
    };
    RepositoryFormatter=()=>{};
    setSelect2CssStyle();
})(jQuery);
