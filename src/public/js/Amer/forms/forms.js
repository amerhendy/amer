jQuery(document).ready(function() {
    initializeFieldsWithJavascript=function(container){
        var selector;
        if (container instanceof jQuery) {
            selector = container;
        } else {
            selector = $(container);
        }
        selector.find("[data-init-function]").each(function(k,v) {
            var element = $(v);
            var functionName = element.data('init-function');
            if (typeof window[functionName] === "function") {
                window[functionName](element);
            }
        });
    }
    mainIntlizing=function(){
        var forms = document.querySelectorAll('form');
        var inputs = document.querySelectorAll('input');
        var selects = document.querySelectorAll('select');
        $.each(forms,function(k,v){
            var uniqueid=SetelementUniqueID($(v));
            window.Amer.forms[uniqueid]={};
            $.each($(v).find($('input, select, textarea, radio, checkbox')),function(l,m){
                var uniqueidInput= SetelementUniqueID($(m));
                window.Amer.forms[uniqueidInput]={};
            });
        });
        //window.Amer.forms
        
        if ((forms.length !== 0) || (inputs.length !== 0) || (selects.length !== 0)) {
            initializeFieldsWithJavascript('form');
        }
    }
    StopEnter=function(){
        $('form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
        $(document).keydown(function(e) {
            if ((e.which == '115' || e.which == '83' ) && (e.ctrlKey || e.metaKey))
            {
                e.preventDefault();
                $("button[type=submit]").trigger('click');
                return false;
            }
            return true;
        });
    };
    changeTabIfNeededAndDisplayErrors=function(form) {
        // we get the first erroed field
        var $firstErrorField = form.find(":invalid").first();
        // we find the closest tab
        var $closestTab = $($firstErrorField).closest('.tab-pane');
        // if we found the tab we will change to that tab before reporting validity of form
        if($closestTab.length) {
            var id = $closestTab.attr('id');
                // switch tabs
                $('.nav a[href="#' + id + '"]').tab('show');
        }
        reportValidity(form);
    }
    reportValidity=function(form){
        // the condition checks if `reportValidity` is defined in the form (browser compatibility)
        if (form[0].reportValidity) {
            // hide the save actions drop down if open
            $('#saveActions').find('.dropdown-menu').removeClass('show');
            // validate and display form errors
            form[0].reportValidity();
        }
    }
    checkFormValidity=function(form) {
        if (form[0].checkValidity) {
            return form[0].checkValidity();
        }
        return false;
    }
    SetSaveActions=function(){
        var selector = $('#bpSaveButtonsGroup').next();    
        var form = $(selector).closest('form');
        var saveActionField = $('[name="_save_action"]');
        var $defaultSubmitButton = $(form).find(':submit');
        $($defaultSubmitButton).on('click', function(e) {
            e.preventDefault();
            $saveAction = $(this).children('span').eq(1);
            if(checkFormValidity(form)) {
                saveActionField.val( $saveAction.attr('data-value') );
                form[0].requestSubmit();
            }else{
                changeTabIfNeededAndDisplayErrors(form);
            }
        });
        var saveActions = $('#saveActions'),
                AmerForm        = saveActions.parents('form'),
                saveActionField = $('[name="save_action"]');
                saveActions.on('click', '.dropdown-menu a', function(){
                    var saveAction = $(this).data('value');
                    saveActionField.val( saveAction );
                    AmerForm.submit();
                });
        AmerForm.submit(function (event) {
            $("button[type=submit]").prop('disabled', true);
            });
        $(selector).find('button').each(function() {
            $(this).click(function(e) {
                if (checkFormValidity(form)) {
                    var saveAction = $(this).data('value');
                    saveActionField.val( saveAction );
                    form[0].requestSubmit();
                }else{
                    changeTabIfNeededAndDisplayErrors(form);
                }
                e.stopPropagation();    
            });
        });
    }
    FormDataToArray=function($form){
        if ($form instanceof jQuery){
            $form=$form[0];
        }
        var formData = new FormData($form);
        var object = {};
        formData.forEach((value, key) => {
            // Reflect.has in favor of: object.hasOwnProperty(key)
            if(!Reflect.has(object, key)){
                object[key] = value;
                return;
            }
            if(!Array.isArray(object[key])){
                object[key] = [object[key]];    
            }
            object[key].push(value);
        });
        return object;
    }
    select2all=function (e){
        $(e).attr('for');
        if($(e).is(":checked")){
            $("#"+$(e).attr('for')+" > option").prop("selected","selected");
            $("#"+$(e).attr('for')).trigger("change");
        }else{
            $("#"+$(e).attr('for')+" > option").prop("selected",false);
            $("#"+$(e).attr('for')).trigger("change");
        }
    }
    set_select2_element=function (element) {
        element.select2({
            dor: 'rtl',
            dropdownAutoWidth: true,
            theme: "bootstrap"
        }).on('select2:unselect', function(e) {
            if ($(this).attr('multiple') && $(this).val().length == 0) {
                alert
                $(this).val(null).trigger('change');
            }
        });
    }
    addOptionToSelect=function (sel, txt, val, obj) {
        var opt = document.createElement('option');
        opt.appendChild(document.createTextNode(txt));
    
        if (typeof val === 'string') {
            opt.value = val;
        }
    
        if (!obj) {
            sel.appendChild(opt);
            return;
        }
    
        var group;
        var el = (typeof obj.el === 'object') ? obj.el : (typeof obj.idx === 'number') ? sel.options[obj.idx] : null;
    
        if (el) {
            // not sel.insertBefore in case optgroup contains
            el.parentNode.insertBefore(opt, el);
            return;
        }
    
        var groups = sel.getElementsByTagName('optgroup');
    
        if (typeof obj.grp === 'number') {
            group = groups[obj.grp];
        } else if (typeof obj.lbl === 'string') {
    
            for (var i = 0, len = groups.length; i < len; i++) {
                if (groups[i].label === obj.lbl) {
                    group = groups[i];
                    break;
                }
            }
        }
    
        if (group) {
            group.appendChild(opt);
        }
        return;
    }
    removeAllOptions=function (sel, removeGrp) {
        //sel=document.getElementById('jobnameselect')
        if(sel.localName !== 'select'){return;}
        var len, groups, par;
        if (removeGrp) {
            groups = sel.getElementsByTagName('optgroup');
            len = groups.length;
            for (var i = len; i; i--) {
                sel.removeChild(groups[i - 1]);
            }
    
        }
        len = sel.options.length;
        for (var i = len; i; i--) {
            par = sel.options[i - 1].parentNode;
            par.removeChild(sel.options[i - 1]);
        }
    }
    removeOption=function (sel, opt) {
        var el = (typeof opt === 'object') ? opt : (typeof opt === 'number') ? sel.options[opt] : null;
    
        if (el) {
            // not sel.removeChild in case optgroup contains
            el.parentNode.removeChild(el);
        }
    
    }
    removeOptGroup=function (sel, grp) {
        var group;
        var groups = sel.getElementsByTagName('optgroup');
    
        if (typeof grp === 'number') {
            group = groups[grp];
        } else if (typeof grp === 'string') {
    
            for (var i = 0, len = groups.length; i < len; i++) {
                if (groups[i].label === grp) {
                    group = groups[i];
                    break;
                }
            }
        }
    
        if (group) {
            sel.removeChild(group);
        }
    
    }
    get_selected=function (element) {
        return $('#' + element).find(':selected').val();
    }
    SetelementUniqueID=function(element){
        if($(element).id() == undefined){
            if($(element).name() === undefined){
                if($(element).attr('type') === undefined){
                    $uid=element[0].nodeName+generateUUID().split('-')[0]
                }else{
                    $uid=$(element).attr('type')+generateUUID().split('-')[0]
                }
                $(element).attr('name',$uid);
            }
            $(element).attr('id',$(element).name());
        }
        if($(element).id().includes('[]')){
            $(element).attr('id',$(element).id().replace('[]', ''));
        }
        Uniqueid=generateUUID().split('-')[0];
        $(element).attr('Uniqueid',Uniqueid);
        return Uniqueid;
    }
    setRequiredFields=function(){
        if(window.Amer.requiredfields == undefined){
            window.Amer.requiredfields=new Array();
        }
        $.each($('input, select, textarea, radio, checkbox'),function(k,v){
            if($(v).required()){
                window.Amer.requiredfields.push($(v).name());
            }
        });
        $.each(window.Amer.requiredfields,function(k,v){
            $('input, select, textarea, radio, checkbox [name='+v+']').attr('required','required');
            var osa=new AmerField(v);
            osa.require(true);
            $(window.Amer.fieldfeed).insertAfter($('input[name='+v+']'));
        });
    }
    setFocusFields=function()
    {
        if(window.Amer.getAutoFocusOnFirstField == false || window.Amer.getAutoFocusOnFirstField == "false" || window.Amer.getAutoFocusOnFirstField == "" || window.Amer.getAutoFocusOnFirstField == undefined){return;}
        if(window.Amer.focusField == false || window.Amer.focusField == "false" || window.Amer.focusField == ""){
            window.Amer.focusField=$('form[name=form]').find('input, select, textarea, radio, checkbox').not('[type=hidden]').eq(0);
            window.Amer.focusField=$(window.Amer.focusField).id();
        }
        if(window.Amer.focusField == undefined){return;}
        window.Amer.focusField=$('#'+window.Amer.focusField).eq(0);
        fieldOffset = window.Amer.focusField.offset().top,
        scrollTolerance = $(window).height() / 2;
        window.Amer.focusField.trigger('focus');
        if( fieldOffset > scrollTolerance ){
            $('html, body').animate({scrollTop: (fieldOffset - 30)});
        }
        
    }
    setformErrors=function(){
        if(window.Amer.FormErrors == false){return;}
        $.each(window.Amer.errors, function(property, messages){
            var normalizedProperty = property.split('.').map(function(item, index){
                return index === 0 ? item : '['+item+']';
            }).join('');
            var field = $('[name="' + normalizedProperty + '[]"]').length ?
                        $('[name="' + normalizedProperty + '[]"]') :
                        $('[name="' + normalizedProperty + '"]'),
                        container = field.parents('.form-group');
            container.addClass('text-danger');
            container.children('input, textarea, select').addClass('is-invalid');
            $.each(messages, function(key, msg){
                var row = $('<div class="invalid-feedback d-block">' + msg + '</div>');
                row.appendTo(container);
                if(window.Amer.tabsEnabled !== false){
                    var tab_id = $(container).closest('[role="tabpanel"]').attr('id');
                    $("#form_tabs [aria-controls="+tab_id+"]").addClass('text-danger');
                }
            });
        });
    }
    mainIntlizing();
    setRequiredFields();
    StopEnter();
    setFocusFields();
    setformErrors();
    if(document.getElementById('saveActions')){
        SetSaveActions();
    }
    $("a[data-toggle='tab']").click(function(){
        currentTabName = $(this).attr('tab_name');
        $("input[name='current_tab']").val(currentTabName);
    });
    if (window.location.hash) {
        $("input[name='current_tab']").val(window.location.hash.substr(1));
    }    
});
