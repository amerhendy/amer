@if ($Amer->hasAccess('BulkDelete') && $Amer->get('list.bulkActions'))
	<a href="javascript:void(0)" onclick="bulkDeleteEntries(this)" class="btn btn-sm btn-danger bulk-button" data-toggle="tooltip" title=" {{ trans('AMER::actions.delete') }}"><i class="fa fa-trash"></i></a>
@endif
@push('after_scripts')
@loadOnce('bulkDeleteEntries')
<script>
	if (typeof bulkDeleteEntries != 'function') {
	  function bulkDeleteEntries(button) {

	      if (typeof Amer.checkedItems === 'undefined' || Amer.checkedItems.length == 0)
	      {
	      	new Noty({
	          type: "warning",
	          text: "<strong>{!! trans('AMER::actions.bulk_no_entries_selected_title') !!}</strong><br>{!! trans('AMER::actions.bulk_no_entries_selected_message') !!}"
	        }).show();

	      	return;
	      }

	      var message = ("{!! trans('AMER::actions.bulk_delete_are_you_sure') !!}").replace(":number", Amer.checkedItems.length);
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
				title: "{!! trans('AMER::actions.warning') !!}",
				text: message,
				icon: "warning",
				showCancelButton: true,
				confirmButtonText: "<span class='fa fa-trash'></span>",
				cancelButtonText: "<span class='fa fa-cancel'></span>",
				showLoaderOnConfirm: true,
			}).then((result) => {
				if (result.isConfirmed) {
					var ajax_calls = [];
		      		var delete_route = "{{Route($Amer->routelist['Bulkdelete']['as'])}}";
					  jQuery.ajax({
						url: delete_route,
						type: "{{$Amer->routelist['Bulkdelete']['methods'][0]}}",
						data: { entries: Amer.checkedItems },
						error: function(result,xhr, ajaxOptions, thrownError) {
								new Noty({
									type: "error",
									text: "<strong>{!! trans('AMER::errors.bulk_delete_error_title') !!}</strong><br> {!! trans('AMER::errors.bulk_delete_error_message') !!}",
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
									text: "<strong>{!! trans('AMER::actions.bulk_delete_sucess_title') !!}</strong><br> {!! trans('AMER::actions.bulk_delete_sucess_message') !!}"+dod,
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
											title: "{!! trans('AMER::errors.bulk_delete_error_title') !!}",
											text: "{!! trans('AMER::errors.bulk_delete_error_message') !!}",
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
						title: "{!! trans('AMER::actions.bulk_delete_cancel_title') !!}",
	                            text: "{!! trans('AMER::actions.bulk_delete_cancel_message') !!}",
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

@endpush
@endLoadOnce