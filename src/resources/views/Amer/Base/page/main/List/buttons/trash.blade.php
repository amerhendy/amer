<?php
if ($Amer->hasAccess('trash'))
	{
		echo'<a 
		href="javascript:void(0)" 
		onclick="trashEntry(this)" 
		data-route="'.Route($Amer->routelist['trash']['as'],$entry->getKey()).'" 
		data-toggle="tooltip" title="'.trans('AMER::actions.trash').'"
		class="btn btn-sm btn-warning" 
		 data-button-type="trash"><i class="fa fa-trash"></i></a>';
	}
?>
@loadOnce('trash_button_script')
@push('after_scripts')	
@if (request()->ajax()) @endpush @endif
<script>
	if (typeof trashEntry != 'function') {
	  	$("[data-button-type=trash]").unbind('click');
	  	function trashEntry(button) {
			var route = $(button).attr('data-route');
			const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-danger',
				cancelButton: 'btn btn-success'
			},
			buttonsStyling: false
			});
			swalWithBootstrapButtons.fire({
				title: "{!! trans('AMER::actions.warning') !!}",
				text: "{!! trans('AMER::actions.trash_confirm') !!}",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: "{!! trans('AMER::actions.trash') !!}",
				cancelButtonText: "{!! trans('AMER::actions.cancel') !!}",
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
							  Amer.table.draw(true);
						  }

			          	  // Show a success notification bubble
			              new Noty({
		                    type: "success",
		                    text: "{!! '<strong>'.trans('AMER::actions.trash_confirmation_title').'</strong><br>'.trans('AMER::actions.trash_confirmation_message') !!}"
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
				              	title: "{!! trans('AMER::actions.trash_confirmation_title') !!}",
	                            text: "{!! trans('AMER::actions.trash_confirmation_not_message') !!}",
				              	icon: "error",
				              	timer: 4000,
				              	buttons: false,
				              });
			          	  }
					  }
						},
						error: function(result,xhr, ajaxOptions, thrownError) {
							swalWithBootstrapButtons.fire({
								title: "{!! trans('AMER::actions.trash_confirmation_title') !!}",
								text: "{!! trans('AMER::actions.trash_confirmation_not_message') !!}",
								icon: "error",
								timer: 4000,
								buttons: false,
							});
			      		}
					});
					
				} else if (result.dismiss === Swal.DismissReason.cancel) {
					swalWithBootstrapButtons.fire({
						title: "{!! trans('AMER::actions.trash_confirmation_title') !!}",
	                            text: "{!! trans('AMER::actions.trash_confirmation_not_message') !!}",
				              	icon: "error",
				              	timer: 4000,
				              	buttons: false,
					}
					)
				}
				})

		}
	}
	</script>
@if (!request()->ajax()) @endpush @endif
@endLoadOnce
