@if ($Amer->hasAccess('bulkClone') && $Amer->get('list.bulkActions'))
	<a href="javascript:void(0)" onclick="bulkCloneEntries(this)" class="btn btn-sm btn-info bulk-button" data-toggle="tooltip" title="{{trans('AMER::actions.clone')}}"><i class="fa fa-copy"></i></a>
@endif
@loadOnce('bulkCloneEntries')
@push('after_scripts')
<script>
	if (typeof bulkCloneEntries != 'function') {
	  function bulkCloneEntries(button) {
	      if (typeof Amer.checkedItems === 'undefined' || Amer.checkedItems.length == 0)
	      {
  	        new Noty({
	          type: "warning",
	          text: "<strong>{!! trans('AMER::actions.bulk_no_entries_selected_title') !!}</strong><br>{!! trans('AMER::actions.bulk_no_entries_selected_message') !!}"
	        }).show();

	      	return;
	      }

	      var message = "{!! trans('AMER::actions.bulk_clone_are_you_sure') !!}";
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
			title: "{!! trans('AMER::actions.warning') !!}",
			text: message,
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "<span class='fa fa-copy'></span>",
			cancelButtonText: "<span class='fa fa-cancel'></span>",
			showLoaderOnConfirm: true,
			}).then((result) => {
				if (result.isConfirmed) {
					var ajax_calls = [];
		      		var clone_route = "{{Route($Amer->routelist['bulkClone']['as'])}}";
					  jQuery.ajax({
						url:clone_route,
						type: "{{$Amer->routelist['bulkClone']['methods'][0]}}",
						data: { entries: Amer.checkedItems },
						error: function(result,xhr, ajaxOptions, thrownError) {
							new Noty({
									type: "error",
									text: "<strong>{!! trans('AMER::errors.bulk_clone_error_title') !!}</strong><br> {!! trans('AMER::errors.bulk_clone_error_message') !!}",
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
									text: "<strong>{!! trans('AMER::actions.bulk_clone_sucess_title') !!}</strong><br> {!! trans('AMER::actions.bulk_clone_sucess_message') !!}"+dod,
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
				              	title: "{!! trans('AMER::errors.trash_confirmation_title') !!}",
	                            text: "{!! trans('AMER::errors.trash_confirmation_not_message') !!}",
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
						title: "{!! trans('AMER::actions.bulk_clone_confirmation_title') !!}",
	                            text: "{!! trans('AMER::actions.bulk_clone_confirmation_cancel') !!}",
				              	icon: "error",
				              	timer: 4000,
				              	buttons: false,
					}
					)
				}

			});
      }
	}
</script>
@endLoadOnce
@endpush