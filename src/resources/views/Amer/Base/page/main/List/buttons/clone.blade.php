@if ($Amer->hasAccess('clone'))
	<a href="javascript:void(0)" 
  onclick="cloneEntry(this)" 
  data-route="{{ Route($Amer->routelist['clone']['as'],$entry->getKey()) }}" 
  data-toggle="tooltip" title="{{trans('AMER::actions.clone')}}"
	class="btn btn-sm btn-info"
  data-button-type="clone"><i class="fa fa-copy"></i></a>
@endif
@loadOnce('clone_button_script')
@push('after_scripts')
 @if (request()->ajax()) @endpush @endif
<script>
	if (typeof cloneEntry != 'function') {
	  $("[data-button-type=clone]").unbind('click');

	  function cloneEntry(button) {
	      // ask for confirmation before deleting an item
	      // e.preventDefault();
	      var button = $(button);
	      var route = button.attr('data-route');

          $.ajax({
              url: route,
              type: 'POST',
              success: function(result) {
                  // Show an alert with the result
                  new Noty({
                    type: "success",
                    text: "{!! trans('AMER::actions.clone_success') !!}"
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
                    text: "{!! trans('AMER::actions.clone_failure') !!}"
                  }).show();
              }
          });
      }
	}
</script>
@if (!request()->ajax()) @endpush @endif
@endLoadOnce
