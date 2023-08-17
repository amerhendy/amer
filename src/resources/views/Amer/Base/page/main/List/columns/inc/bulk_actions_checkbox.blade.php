@if (!isset($entry))
    <span class="Amer_bulk_actions_checkbox">
        <input type="checkbox" class="Amer_bulk_actions_general_checkbox">
    </span>
@else
    <span class="Amer_bulk_actions_checkbox">
        <input type="checkbox" class="Amer_bulk_actions_line_checkbox" data-primary-key-value="{{ $entry->getKey() }}">
    </span>

    @loadOnce('bpFieldInitCheckboxScript')
    <script>
    if (typeof addOrRemoveAmerCheckedItem !== 'function') {
        function addOrRemoveAmerCheckedItem(element) {
            Amer.lastCheckedItem = false;

            document.querySelectorAll('input.Amer_bulk_actions_line_checkbox').forEach(checkbox => checkbox.onclick = e => {
                e.stopPropagation();

                let checked = checkbox.checked;
                let primaryKeyValue = checkbox.dataset.primaryKeyValue;

                Amer.checkedItems ??= [];
                
                if (checked) {
                    // add item to Amer.checkedItems variable
                    Amer.checkedItems.push(primaryKeyValue);

                    // if shift has been pressed, also select all elements
                    // between the last checked item and this one
                    if (Amer.lastCheckedItem && e.shiftKey) {
                        let getNodeindex = elm => [...elm.parentNode.children].indexOf(elm);
                        let first = document.querySelector(`input.Amer_bulk_actions_line_checkbox[data-primary-key-value="${Amer.lastCheckedItem}"]`).closest('tr');
                        let last = document.querySelector(`input.Amer_bulk_actions_line_checkbox[data-primary-key-value="${primaryKeyValue}"]`).closest('tr');
                        let firstIndex = getNodeindex(first);
                        let lastIndex = getNodeindex(last)
                        
                        while(first !== last) {
                            first = firstIndex < lastIndex ? first.nextElementSibling : first.previousElementSibling;
                            first.querySelector('input.Amer_bulk_actions_line_checkbox:not(:checked)')?.click();
                        }
                    }

                    // remember that this one was the last checked item
                    Amer.lastCheckedItem = primaryKeyValue;
                } else {
                    // remove item from Amer.checkedItems variable
                    let index = Amer.checkedItems.indexOf(primaryKeyValue);
                    if (index > -1) Amer.checkedItems.splice(index, 1);
                }

                // if no items are selected, disable all bulk buttons
                enableOrDisableBulkButtons();
            });
        }
    }

    if (typeof markCheckboxAsCheckedIfPreviouslySelected !== 'function') {
        function markCheckboxAsCheckedIfPreviouslySelected() {
            let checkedItems = Amer.checkedItems ?? [];
            let pageChanged = localStorage.getItem('page_changed') ?? false;
            let tableUrl = Amer.table.ajax.url();
            let hasFilterApplied = false;

            if (tableUrl.indexOf('?') > -1) {
                if (tableUrl.substring(tableUrl.indexOf('?') + 1).length > 0) {
                    hasFilterApplied = true;
                }
            }

            // if it was not a page change, we check if datatables have any search, or the url have any parameters.
            // if you have filtered entries, and then remove the filters we are sure the entries are in the table.
            // we don't remove them in that case.
            if (! pageChanged && (Amer.table.search().length !== 0 || hasFilterApplied)) {
                Amer.checkedItems = [];
            }
            document
                .querySelectorAll('input.Amer_bulk_actions_line_checkbox[data-primary-key-value]')
                .forEach(function(elem) {
                    let checked = checkedItems.length && checkedItems.indexOf(elem.dataset.primaryKeyValue) > -1;
                    elem.checked = checked;
                    if (checked && Amer.checkedItems.indexOf(elem.dataset.primaryKeyValue) === -1) {
                        Amer.checkedItems.push(elem.dataset.primaryKeyValue);
                    }
                });
            
            localStorage.removeItem('page_changed');
        }
    }

    if (typeof addBulkActionMainCheckboxesFunctionality !== 'function') {
        function addBulkActionMainCheckboxesFunctionality() {
            let mainCheckboxes = Array.from(document.querySelectorAll('input.Amer_bulk_actions_general_checkbox'));
            let rowCheckboxes = Array.from(document.querySelectorAll('input.Amer_bulk_actions_line_checkbox'));

            mainCheckboxes.forEach(checkbox => {
                // set initial checked status
                checkbox.checked = document.querySelectorAll('input.Amer_bulk_actions_line_checkbox:not(:checked)').length === 0;

                // when the Amer_bulk_actions_general_checkbox is selected, toggle all visible checkboxes
                checkbox.onclick = event => {
                    rowCheckboxes.filter(elem => checkbox.checked !== elem.checked).forEach(elem => elem.click());
                    
                    // make sure the other checkbox has the same checked status
                    mainCheckboxes.forEach(elem => elem.checked = checkbox.checked);

                    event.stopPropagation();
                }
            });

            // Stop propagation of href on the first column
            document.querySelectorAll('table td.dtr-control a').forEach(link => link.onclick = e => e.stopPropagation());
        }
    }

    if (typeof enableOrDisableBulkButtons !== 'function') {
        function enableOrDisableBulkButtons() {
            document.querySelectorAll('.bulk-button').forEach(btn => btn.classList.toggle('disabled', !Amer.checkedItems?.length));
        }
    }

    Amer.addFunctionToDataTablesDrawEventQueue('addOrRemoveAmerCheckedItem');
    Amer.addFunctionToDataTablesDrawEventQueue('markCheckboxAsCheckedIfPreviouslySelected');
    Amer.addFunctionToDataTablesDrawEventQueue('addBulkActionMainCheckboxesFunctionality');
    Amer.addFunctionToDataTablesDrawEventQueue('enableOrDisableBulkButtons');
    </script>
    @endLoadOnce
@endif
