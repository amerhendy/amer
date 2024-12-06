(function(){
    $.ajaxSetup({ cache: true });
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    setTimeout(() => {
        $('[data-toggle="tooltip"]').tooltip();
      }, 3000)
    unbindbtns=function(access,action=null){
        if(action == null){
            action='click';
        }
        $(`[data-button-type=${access}]`).unbind(action);
        
    }
    deleteEntry=function (button) {
        var route = $(button).attr('data-route');
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-success'
        },
        buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: jstrans['actions']['warning'],
            text: jstrans['actions']['delete_confirm'],
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: jstrans['actions']['delete'],
            cancelButtonText: jstrans['actions']['cancel'],
            reverseButtons: true,
            dangerMode: true,
            showLoaderOnConfirm: true,
            }).then((result) => {
            if (result.isConfirmed) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                jQuery.ajax({
                    url:route,
                    type: 'DELETE',
                    //data: {_token: CSRF_TOKEN},
                    //dataType: 'JSON',
                    success: function (result) {
                        if (result == 1) {
                    // Redraw the table
                    if (typeof Amer != 'undefined' && typeof Amer.table != 'undefined') {
                        // Move to previous page in case of deleting the only item in table
                        if(Amer.table.rows().count() === 1) {
                            Amer.table.page("previous");
                        }

                        Amer.table.draw(false);
                    }
                        // Show a success notification bubble
                    new Noty({
                        type: "success",
                        text: `<strong>${jstrans['actions']['delete_confirmation_title']}</strong><br>${jstrans['actions']['delete_confirmation_message']}`
                    }).show();

                    // Hide the modal, if any
                    $('.modal').modal('hide');
                }else{
                    if (result instanceof Object) {
                            // trigger one or more bubble notifications
                            Object.entries(result).forEach(function(entry, index) {
                            var type = entry[0];
                            entry[1].forEach(function(message, i) {
                                new Noty({
                                type: type,
                                text: message
                            }).show();
                            });
                            });
                        } else {// Show an error alert
                        swal({
                            title:jstrans['actions']['delete_confirmation_not_title'],
                            text: jstrans['actions']['delete_confirmation_not_message'],
                            icon: "error",
                            timer: 4000,
                            buttons: false,
                        });
                        }
                }
                    },
                    error: function(result,xhr, ajaxOptions, thrownError) {
                        swalWithBootstrapButtons.fire({
                            title:jstrans['actions']['delete_confirmation_not_title'],
                            text: jstrans['actions']['delete_confirmation_not_message'],
                            icon: "error",
                            timer: 4000,
                            buttons: false,
                        });
                    }
                });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title:jstrans['actions']['delete_confirmation_not_title'],
                    text:jstrans['actions']['delete_confirmation_not_message'],
                    icon: "error",
                    timer: 4000,
                    buttons: false,
                }
                )
            }
            })

    }
    bulkCloneEntries=function (button) {
    
    if (typeof Amer.checkedItems === 'undefined' || Amer.checkedItems.length == 0)
    {

        new Noty({
        type: "warning",
        text: `<strong>${jstrans['actions']['bulk_no_entries_selected_title']}</strong><br>${jstrans['actions']['bulk_no_entries_selected_message']}`
        }).show();

        return;
    }

    var message = jstrans['actions']['bulk_clone_are_you_sure'];
    message = message.replace(":number", Amer.checkedItems.length);
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
        });
    // show confirm message
    swalWithBootstrapButtons.fire({
        title: jstrans['actions']['warning'],
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "<span class='fa fa-copy'></span>",
        cancelButtonText: "<span class='fa fa-cancel'></span>",
        showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.isConfirmed) {
                var ajax_calls = [];
                jQuery.ajax({
                    url:window.opLinks.bulkClone,
                    type: window.opLinks.bulkCloneMethod,
                    data: { entries: Amer.checkedItems },
                    error: function(result,xhr, ajaxOptions, thrownError) {
                        new Noty({
                                type: "error",
                                text: `<strong>${jstrans['errors']['bulk_clone_error_title']}</strong><br>${jstrans['errors']['bulk_clone_error_message']}`,
                            }).show();
                            Amer.checkedItems = [];
                            Amer.table.draw(false);
                    },
                    success: function (results) {
                        $.each(results,function(k,v){
                            if(v == 1){
                                var dod=$('input[type="checkbox"][data-primary-key-value="'+k+'"]').parent().next().text();
                                $('input[type="checkbox"][data-primary-key-value="'+k+'"]').prop('checked',false);
                                new Noty({
                                type: "success",
                                text: `<strong>${jstrans['actions']['bulk_clone_sucess_title']}</strong><br>${jstrans['actions']['bulk_clone_sucess_message']} ${dod}`,
                            }).show();
                            Amer.checkedItems=[];
                            Amer.table.draw(false);
                            }else{
                                if (result instanceof Object) {
                            // trigger one or more bubble notifications 
                            Object.entries(result).forEach(function(entry, index) {
                            var type = entry[0];
                            entry[1].forEach(function(message, i) {
                                new Noty({
                                type: type,
                                text: message
                            }).show();
                            });
                            });
                        } else {// Show an error alert
                        swal({
                            title:jstrans['errors']['trash_confirmation_title'],
                            text:jstrans['errors']['trash_confirmation_not_message'],
                            icon: "error",
                            timer: 4000,
                            buttons: false,
                        });
                        }
                            }
                        });
                    }

                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: jstrans['actions']['bulk_clone_confirmation_title'],
                    text: jstrans['actions']['bulk_clone_confirmation_cancel'],
                            icon: "error",
                            timer: 4000,
                            buttons: false,
                }
                )
            }

        });
    }
    bulkDeleteEntries=function (button) {
        if (typeof Amer.checkedItems === 'undefined' || Amer.checkedItems.length == 0)
        {
            new Noty({
            type: "warning",
            text: `<strong>${jstrans['actions']['bulk_no_entries_selected_title']}</strong><br>${jstrans['actions']['bulk_no_entries_selected_message']}`
          }).show();

            return;
        }

        var message = (jstrans['actions']['bulk_delete_are_you_sure']).replace(":number", Amer.checkedItems.length);
        var button = $(this);

        // show confirm message
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
              confirmButton: 'btn btn-danger',
              cancelButton: 'btn btn-success'
          },
          buttonsStyling: false
          });
          swalWithBootstrapButtons.fire({
              title: jstrans['actions']['warning'],
              text: message,
              icon: "warning",
              showCancelButton: true,
              confirmButtonText: "<span class='fa fa-trash'></span>",
              cancelButtonText: "<span class='fa fa-cancel'></span>",
              showLoaderOnConfirm: true,
          }).then((result) => {
              if (result.isConfirmed) {
                  var ajax_calls = [];
                    jQuery.ajax({
                      url: window.opLinks.BulkDelete,
                      type: window.opLinks.BulkDeleteMethod,
                      data: { entries: Amer.checkedItems },
                      error: function(result,xhr, ajaxOptions, thrownError) {
                              new Noty({
                                  type: "error",
                                  text: `<strong>${jstrans['errors']['bulk_delete_error_title']}</strong><br>${jstrans['errors']['bulk_delete_error_message']}`,
                              }).show();
                              Amer.checkedItems = [];
                              Amer.table.draw(false);
                        },
                        success: function (results) {
                          $.each(results,function(k,v){
                              if(v == 1){
                                  var dod=$('input[type="checkbox"][data-primary-key-value="'+k+'"]').parent().next().text();
                                  $('input[type="checkbox"][data-primary-key-value="'+k+'"]').prop('checked',false);
                                  new Noty({
                                  type: "success",
                                  text: `<strong>${jstrans['actions']['bulk_delete_sucess_title']}</strong><br>${jstrans['actions']['bulk_delete_sucess_message']} ${dod}`,
                              }).show();
                              }else{
                                  if (result instanceof Object) {
                                      // trigger one or more bubble notifications
                                      Object.entries(result).forEach(function(entry, index) {
                                      var type = entry[0];
                                      entry[1].forEach(function(message, i) {
                                          new Noty({
                                              type: type,
                                              text: message
                                          }).show();
                                      });
                                      });
                                  } else {// Show an error alert
                                      swal({
                                          title:jstrans['errors']['bulk_delete_error_title'],
                                          text:jstrans['errors']['bulk_delete_error_message'],
                                          icon: "error",
                                          timer: 4000,
                                          buttons: false,
                                      });
                                  }
                              }
                          });
                          if(Amer.table.rows().count() === Amer.checkedItems.length) {
                              Amer.table.page("previous");
                          }

                          Amer.checkedItems = [];
                          Amer.table.draw(false);
                        }
                    });
              }
              else if (result.dismiss === Swal.DismissReason.cancel) {
                  swalWithBootstrapButtons.fire({
                        title:jstrans['actions']['bulk_delete_cancel_title'],
                        text:jstrans['actions']['bulk_delete_cancel_message'],
                        icon: "error",
                        timer: 4000,
                        buttons: false,
                  }
                  )
              }
          });
    }
    cloneEntry=function (button) {
        var button = $(button);
        var route = button.attr('data-route');
        $.ajax({
            url: route,
            type: 'POST',
            success: function(result) {
                // Show an alert with the result
                new Noty({
                  type: "success",
                  text: jstrans['actions']['clone_success'],
                }).show();

                // Hide the modal, if any
                $('.modal').modal('hide');

                if (typeof Amer !== 'undefined') {
                  Amer.table.draw(false);
                }
            },
            error: function(result) {
                // Show an alert with the result
                new Noty({
                  type: "warning",
                  text: jstrans['actions']['clone_failure']
                }).show();
            }
        });
    }
    trashEntry=(button)=>{
        var route = $(button).attr('data-route');
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-success'
        },
        buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: jstrans['actions']['warning'],
            text: jstrans['actions']['trash_confirm'],
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: jstrans['actions']['trash'],
            cancelButtonText: jstrans['actions']['cancel'],
            reverseButtons: true,
            dangerMode: true,
            showLoaderOnConfirm: true,
            }).then((result) => {
            if (result.isConfirmed) {
                jQuery.ajax({
                    url:route,
                    type: 'post',
                    success: function (results) {
                        if (results == 1 || results == '1') {
                      // Redraw the table
                      if (typeof Amer != 'undefined' && typeof Amer.table != 'undefined') {
                        $(button).parent().parent().remove()
                          // Move to previous page in case of deleting the only item in table
                          if(Amer.table.rows().count() === 1) {
                            Amer.table.page("previous");
                          }
                          //Amer.table.draw(true);
                      }

                        // Show a success notification bubble
                      new Noty({
                        type: "success",
                        text: `<strong>${jstrans['actions']['trash_confirmation_title']}<br>${jstrans['actions']['trash_confirmation_message']}`
                      }).show();
                      // Hide the modal, if any
                      $('.modal').modal('hide');
                  }else{
                    if (result instanceof Object) {
                            // trigger one or more bubble notifications
                            Object.entries(result).forEach(function(entry, index) {
                              var type = entry[0];
                              entry[1].forEach(function(message, i) {
                                new Noty({
                                type: type,
                                text: message
                              }).show();
                              });
                            });
                        } else {// Show an error alert
                          swal({
                                title:jstrans['actions']['trash_confirmation_title'],
                                text:jstrans['actions']['trash_confirmation_not_message'],
                                icon: "error",
                                timer: 4000,
                                buttons: false,
                          });
                        }
                  }
                    },
                    error: function(result,xhr, ajaxOptions, thrownError) {
                        swalWithBootstrapButtons.fire({
                            title:jstrans['actions']['trash_confirmation_title'],
                            text:jstrans['actions']['trash_confirmation_not_message'],
                            icon: "error",
                            timer: 4000,
                            buttons: false,
                        });
                      }
                });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title:jstrans['actions']['trash_confirmation_title'],
                    text:jstrans['actions']['trash_confirmation_not_message'],
                    icon: "error",
                    timer: 4000,
                    buttons: false,
                }
                )
            }
            })

    }
})(jQuery)