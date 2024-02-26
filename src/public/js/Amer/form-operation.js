function checkFormValidity(form) {
    if (form[0].checkValidity) {
        return form[0].checkValidity();
    }
    return false;
}
function changeTabIfNeededAndDisplayErrors(form) {
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
function reportValidity(form) {
    // the condition checks if `reportValidity` is defined in the form (browser compatibility)
    if (form[0].reportValidity) {
        // hide the save actions drop down if open
        $('#saveActions').find('.dropdown-menu').removeClass('show');
        // validate and display form errors
        form[0].reportValidity();
    }
}
