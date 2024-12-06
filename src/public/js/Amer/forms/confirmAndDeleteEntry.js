(function(){
    function confirmAndDeleteEntry() {
    //<!-- footer.blade -->
    // Ask for confirmation before deleting an item
    swal({
        title: jstrans.actions.warning,
        text: jstrans.actions.delete_confirm,
        icon: "warning",
        buttons: [jstrans.actions.cancel,jstrans.actions.delete],
        dangerMode: true,
    }).then((value) => {
        if (value) {
            $.ajax({
                url: confirmAndDeleteEntryRoute,
                type: 'DELETE',
                success: function(result) {
                    if (result !== '1') {
                        // if the result is an array, it means
                        // we have notification bubbles to show
                        if (result instanceof Object) {
                            // trigger one or more bubble notifications
                            Object.entries(result).forEach(function(entry) {
                                var type = entry[0];
                                entry[1].forEach(function(message, i) {
                                    new Noty({
                                        type: type,
                                        text: message
                                    }).show();
                                });
                            });
                        } else { // Show an error alert
                            swal({
                                title: jstrans.actions.delete_confirmation_not_title,
                                text: jstrans.actions.delete_confirmation_not_message,
                                icon: "error",
                                timer: 4000,
                                buttons: false,
                            });
                        }
                    }
                    // All is good, show a success message!
                    swal({
                        title: jstrans.actions.delete_confirmation_title,
                        text: jstrans.actions.delete_confirmation_message,
                        icon: "success",
                        buttons: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                    });

                    // Redirect in 1 sec so that admins get to see the success message
                    setTimeout(function () {
                        window.location.href = confirmAndDeleteEntryRedirect;
                    }, 1000);
                },
                error: function() {
                    // Show an alert with the result
                    swal({
                        title: jstrans.actions.delete_confirmation_not_title,
                        text: jstrans.actions.delete_confirmation_not_message,
                        icon: "error",
                        timer: 4000,
                        buttons: false,
                    });
                }
            });
        }
    });
}
})(jQuery);