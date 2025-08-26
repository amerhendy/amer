(function(){
    setSelectAndOrderCssStyle=function(){
        var style = $('style');
        var css=`.select_and_order_all,
        .select_and_order_selected {
            list-style-type: none;
            height: 220px;
            overflow: scroll;
            overflow-x: hidden;
            padding: 0px 5px 5px 5px;
            width: 100%;
        }
        .select_and_order_all li,
        .select_and_order_selected li{
            border: 1px solid #eee;
            margin-top: 5px;
            padding: 5px;
            font-size: 1em;
            overflow: hidden;
            cursor: grab;
            border-style: dashed;
        }
        .select_and_order_all li {
            background: #fbfbfb;
            color: grey;
        }
        .select_and_order_selected li {
            border-style: solid;
        }
        .select_and_order_all li.ui-sortable-helper,
        .select_and_order_selected li.ui-sortable-helper {
            color: #3c8dbc;
            border-collapse: #3c8dbc;
            z-index: 9999;
        }
        .select_and_order_all .ui-sortable-placeholder,
        .select_and_order_selected .ui-sortable-placeholder {
            border: 1px dashed #3c8dbc;
            visibility: visible!important;
        }
        .ui-sortable-handle {
            -ms-touch-action: none;
            touch-action: none;
        }`;
        $(style)[0].append(css)
    }
    setSelectAndOrderCssStyle();
    bpFieldInitSelectAndOrderElement=function(element) {
        var $dragSource = element.find('[data-identifier=drag-source]');
        var $dragDestination = element.find('[data-identifier=drag-destination]');
        var $hiddenSelect = element.find('[data-identifier=results] select');
        var $fieldName = element.attr('data-field-name');
        var $alreadySelectedOptions = $hiddenSelect.data('selected-options');
        var $allOptions = element.data('all-options');

        // selected options should be an array no matter what was received (string or direct array)
        // useful if the selected-options were set by the repeatable field
        if (typeof $alreadySelectedOptions === 'string' ) {
            $alreadySelectedOptions = $alreadySelectedOptions.split(",");
        }

        // set unique IDs on the drag-and-drop areas so we can reference them later on
        var $allId = 'sao_all_'+Math.ceil(Math.random() * 1000000);
        var $selectedId = 'sao_selected_'+Math.ceil(Math.random() * 1000000);

        element.find('[data-identifier=drag-destination]').attr('id', $selectedId);
        element.find('[data-identifier=drag-source]').attr('id', $allId);

        // initialize jQueryUI sortable
        $( "#"+$allId+", #"+$selectedId ).sortable({
            connectWith: "."+$fieldName+"_connectedSortable",
            create: function (event, ui) {
                // populate all options in the right-hand area (aka $dragSource)
                if (Object.keys($allOptions).length) {
                    $dragSource.html("");
                    for (value in $allOptions) {
                        if(isObjext($allOptions[value])){
                            $dragSource.append('<li value="'+$allOptions[value]['id']+'"><i class="text-primary fa fa-arrows"></i> '+$allOptions[value]['text']+'</li>');
                        }else{
                            $dragSource.append('<li value="'+value+'"><i class="text-primary fa fa-arrows-v"></i> '+$allOptions[value]+'</li>');
                        }
                    }
                }

                // populate selected options in the left-hand area (aka $dragDestination)
                if ($alreadySelectedOptions.length) {
                    if ($alreadySelectedOptions.length == 1 && ($alreadySelectedOptions[0] =='' || $alreadySelectedOptions == ' ' ) ) {
                        return;
                    }

                    $dragDestination.html("");
                    $hiddenSelect.html("");

                    $alreadySelectedOptions.forEach(function(value, key) {
                        $dragDestination.append('<li value="'+value+'"><i class="text-primary fa fa-arrows-v"></i> '+$allOptions[value]+'</li>');
                        $dragSource.find('li[value='+value+']').remove();
                        $hiddenSelect.append('<option value="'+value+'" selected></option>');
                    });
                }
            },
            update: function() {
                var updatedlist = $(this).attr('id');
                if((updatedlist == $selectedId)) {
                    // clear all options inside the select
                    $hiddenSelect.html("");
                    // if there are no items dragged inside the selected area, abort
                    if($dragDestination.find('li').length=0) {
                        return;
                    }

                    // for each item dragged inside the selected area
                    // add a new selected option inside the hidden select
                    $dragDestination.find('li').each(function(val,obj) {
                        $hiddenSelect.append('<option value="'+obj.getAttribute('value')+'" selected></option>');
                    });
                }
            }
        }).disableSelection();
    }
})(jQuery);