$('form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
})
const btns = document.querySelectorAll(".btn");
for (var i = 0; i < btns.length; i++) {
    if (btns[i].hasAttribute('data-mdb-ripple-duration')) {} else { btns[i].setAttribute('data-mdb-ripple-duration', '0.1ms'); }
}
function initializeFieldsWithJavascript(container) {
    var selector;
    if (container instanceof jQuery) {
        selector = container;
    } else {
        selector = $(container);
    }
//    console.log(selector);
    selector.find("[data-init-function]").each(function() {
        var element = $(this);
        
        var functionName = element.data('init-function');

        if (typeof window[functionName] === "function") {
            window[functionName](element);
        }
    });
}
function select2all(e){
    //<input type="checkbox" id="selectall" onclick="select2all(this)" for="jobnameselect">Select All
    $(e).attr('for');
    if($(e).is(":checked")){
        $("#"+$(e).attr('for')+" > option").prop("selected","selected");
        $("#"+$(e).attr('for')).trigger("change");
    }else{
        $("#"+$(e).attr('for')+" > option").prop("selected",false);
        $("#"+$(e).attr('for')).trigger("change");
    }
}
function set_select2_element(element) {
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

function addOptionToSelect(sel, txt, val, obj) {
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

function removeAllOptions(sel, removeGrp) {
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

function removeOption(sel, opt) {
    var el = (typeof opt === 'object') ? opt : (typeof opt === 'number') ? sel.options[opt] : null;

    if (el) {
        // not sel.removeChild in case optgroup contains
        el.parentNode.removeChild(el);
    }

}

function removeOptGroup(sel, grp) {
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

function get_selected(element) {
    return $('#' + element).find(':selected').val();
}

jQuery(document).ready(function() {
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
});
