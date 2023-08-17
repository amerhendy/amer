<!-- Backpack Table Field Type -->
<?php
    $max = isset($field['max']) && (int) $field['max'] > 0 ? $field['max'] : -1;
    $min = isset($field['min']) && (int) $field['min'] > 0 ? $field['min'] : -1;
    $item_name = strtolower(isset($field['entity_singular']) && ! empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);
    $items = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';

    if (is_array($items)) {
        if (count($items)) {
            $items = json_encode($items);
        } else {
            $items = '[]';
        }
    } elseif (is_string($items) && ! is_array(json_decode($items))) {
        $items = '[]';
    }

    // make sure columns are defined
    if (! isset($field['columns'])) {
        $field['columns'] = ['value' => 'Value'];
    }
?>
    <input class="array-json"
            type="hidden"
            data-init-function="bpFieldInitTableElement"
            name="{{ $field['name'] }}"
            value="{{ $items }}"
            data-max="{{$max}}"
            data-min="{{$min}}"
            data-maxErrorTitle="{{trans('Amer::crud.table_cant_add', ['entity' => $item_name])}}"
            data-maxErrorMessage="{{trans('Amer::crud.table_max_reached', ['max' => $max])}}">

    <div class="array-container form-group">
        <table class="table table-sm table-striped m-b-0">
            <thead>
                <tr>
                    @foreach( $field['columns'] as $column )
                    <th style="font-weight: 600!important;">
                        {{ $column }}
                    </th>
                    @endforeach
                    <th class="text-center"> {{-- <i class="fa fa-sort"></i> --}} </th>
                    <th class="text-center"> {{-- <i class="fa fa-trash"></i> --}} </th>
                </tr>
            </thead>
            <tbody class="table-striped items sortableOptions">
                <tr class="array-row clonable" style="display: none;">
                    @foreach( $field['columns'] as $column => $label)
                    <td>
                        <input class="form-control form-control-sm" type="text" data-cell-name="item.{{ $column }}">
                    </td>
                    @endforeach
                    <td>
                        <span class="btn btn-sm btn-light sort-handle pull-right"><span class="sr-only">sort item</span><i class="fa fa-sort" role="presentation" aria-hidden="true"></i></span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-light removeItem" type="button"><span class="sr-only">delete item</span><i class="fa fa-trash" role="presentation" aria-hidden="true"></i></button>
                    </td>
                </tr>

            </tbody>

        </table>

        <div class="array-controls btn-group m-t-10">
            <button class="btn btn-sm btn-light" type="button" data-button-type="addItem"><i class="fa fa-plus"></i> {{trans('Amer::crud.add')}} {{ $item_name }}</button>
        </div>

    </div>

    @push('after_scripts')
    @loadScriptOnce('js/jquery/jquery-ui.min.js')
    @loadOnce(bpFieldInitTableElement)
        <script>
            function bpFieldInitTableElement(element) {
                var $tableWrapper = element.parent('[data-field-type=table]');
                var $rows = (element.attr('value') != '') ? $.parseJSON(element.attr('value')) : '';
                var $max = element.attr('data-max');
                var $min = element.attr('data-min');
                var $maxErrorTitle = element.attr('data-maxErrorTitle');
                var $maxErrorMessage = element.attr('data-maxErrorMessage');


                // add rows with the information from the database
                if($rows != '[]') {
                    $.each($rows, function(key) {

                        addItem();

                        $.each(this, function(column , value) {
                            $tableWrapper.find('tbody tr:last').find('input[data-cell-name="item.' + column + '"]').val(value);
                        });

                        // if it's the last row, update the JSON
                        if ($rows.length == key+1) {
                            updateTableFieldJson();
                        }
                    });
                }

                // add minimum rows if needed
                var itemCount = $tableWrapper.find('tbody tr').not('.clonable').length;
                if($min > 0 && itemCount < $min) {
                    $rowsToAdd = Number($min) - Number(itemCount);

                    for(var i = 0; i < $rowsToAdd; i++){
                        addItem();
                    }
                }

                $tableWrapper.find('.sortableOptions').sortable({
                    handle: '.sort-handle',
                    axis: 'y',
                    helper: function(e, ui) {
                        ui.children().each(function() {
                            $(this).width($(this).width());
                        });
                        return ui;
                    },
                    update: function( event, ui ) {
                        updateTableFieldJson();
                    }
                });


                $tableWrapper.find('[data-button-type=addItem]').click(function() {
                    if($max > -1) {
                        var totalRows = $tableWrapper.find('tbody tr').not('.clonable').length;

                        if(totalRows < $max) {
                            addItem();
                            updateTableFieldJson();
                        } else {
                            new Noty({
                              type: "warning",
                              text: "<strong>"+$maxErrorTitle+"</strong><br>"+$maxErrorMessage
                            }).show();
                        }
                    } else {
                        addItem();
                        updateTableFieldJson();
                    }
                });

                function addItem() {
                    $tableWrapper.find('tbody').append($tableWrapper.find('tbody .clonable').clone().show().removeClass('clonable'));
                }

                $tableWrapper.on('click', '.removeItem', function() {
                    var totalRows = $tableWrapper.find('tbody tr').not('.clonable').length;
                    if (totalRows > $min) {
                        $(this).closest('tr').remove();
                        updateTableFieldJson();
                        return false;
                    }
                });

                $tableWrapper.find('tbody').on('keyup', function() {
                    updateTableFieldJson();
                });


                function updateTableFieldJson() {
                    var $rows = $tableWrapper.find('tbody tr').not('.clonable');
                    var $hiddenField = $tableWrapper.find('input.array-json');

                    var json = '[';
                    var otArr = [];
                    var tbl2 = $rows.each(function(i) {
                        x = $(this).children().closest('td').find('input');
                        var itArr = [];
                        x.each(function() {
                            if(this.value.length > 0) {
                                var key = $(this).attr('data-cell-name').replace('item.','');
                                itArr.push('"' + key + '":' + JSON.stringify(this.value));
                            }
                        });
                        otArr.push('{' + itArr.join(',') + '}');
                    })
                    json += otArr.join(",") + ']';

                    var totalRows = $rows.length;

                    $hiddenField.val( totalRows ? json : null );
                }

                // on page load, make sure the input has the old values
                updateTableFieldJson();
            }
        </script>
        @endLoadOnce
    @endpush
