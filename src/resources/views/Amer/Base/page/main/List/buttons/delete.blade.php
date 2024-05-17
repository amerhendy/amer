	<!--buttons.delete.blade-->
<?php
if ($Amer->hasAccess('delete'))
	{
		echo'<a 
		href="javascript:void(0)" 
		onclick="deleteEntry(this)" 
		data-route="'.url($Amer->route.'/'.$entry->getKey()).'" 
		data-toggle="tooltip"
		title="'.trans('AMER::actions.delete').'"
		class="btn btn-sm btn-danger" 
		data-button-type="delete">
		<i class="fa fa-remove"></i>
		</a>';
	}
?>
@loadOnce('tooltip')
@push('after_scripts')
<script>
$(function () {
$('[data-toggle="tooltip"]').tooltip();   
});
</script>
@endpush
@endLoadOnce
@loadOnce('delete_button_script')
@push('after_scripts')
@if (request()->ajax()) @endpush @endif
<script>
	if (typeof deleteEntry != 'function') {
	  	$("[data-button-type=delete]").unbind('click');
	  	function deleteEntry(button) {
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
				text: "{!! trans('AMER::actions.delete_confirm') !!}",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: "{!! trans('AMER::actions.delete') !!}",
				cancelButtonText: "{!! trans('AMER::actions.cancel') !!}",
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
		                    text: "{!! '<strong>'.trans('AMER::actions.delete_confirmation_title').'</strong><br>'.trans('AMER::actions.delete_confirmation_message') !!}"
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
				              	title: "{!! trans('AMER::actions.delete_confirmation_not_title') !!}",
	                            text: "{!! trans('AMER::actions.delete_confirmation_not_message') !!}",
				              	icon: "error",
				              	timer: 4000,
				              	buttons: false,
				              });
			          	  }
					  }
						},
						error: function(result,xhr, ajaxOptions, thrownError) {
							swalWithBootstrapButtons.fire({
								title: "{!! trans('AMER::actions.delete_confirmation_not_title') !!}",
								text: "{!! trans('AMER::actions.delete_confirmation_not_message') !!}",
								icon: "error",
								timer: 4000,
								buttons: false,
							});
			      		}
					});
					
				} else if (result.dismiss === Swal.DismissReason.cancel) {
					swalWithBootstrapButtons.fire({
						title: "{!! trans('AMER::actions.delete_confirmation_not_title') !!}",
	                            text: "{!! trans('AMER::actions.delete_confirmation_not_message') !!}",
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
