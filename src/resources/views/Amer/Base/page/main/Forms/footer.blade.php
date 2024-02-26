</div>
    <div class="card-footer">
        <div id="saveActions" class="form-group">
            <input type="hidden" name="_save_action" value="{{ $saveAction['active']['value'] }}">
            @if(!empty($saveAction['options']))
            <div class="btn-group" role="group">
            @endif
            <button type="submit" class="btn btn-success">
                <span class="fa fa-save" role="presentation" aria-hidden="true"></span>
                <span data-value="{{ $saveAction['active']['value'] }}">{{ $saveAction['active']['label'] }}</span>
            </button>
            @if(!empty($saveAction['options']))
            <div class="btn-group" role="group">
                <button id="bpSaveButtonsGroup"  type="button" class="btn btn-success dropdown-toggle border-end" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="sr-only"><span class="fa fa-caret-down"></span></span>
                </button>
                <div class="dropdown-menu" aria-labelledby="bpSaveButtonsGroup">
                @foreach( $saveAction['options'] as $value => $label)
                    <button type="button" class="dropdown-item btn btn-success " data-value="{{ $value }}">{{$label}}</button>
                @endforeach
                </div>
            </div>
            @endif
            @if(!$Amer->hasOperationSetting('showCancelButton') || $Amer->getOperationSetting('showCancelButton') == true)
            <a href="{{ $Amer->hasAccess('list') ? url($Amer->route) : url()->previous() }}" class="btn btn-danger"><span class="fa fa-ban"></span> &nbsp;{{ trans('AMER::actions.cancel') }}</a>
            @endif
            @if ($Amer->get('update.showDeleteButton') && $Amer->get('delete.configuration') && $Amer->hasAccess('delete'))
            <button onclick="confirmAndDeleteEntry()" type="button" class="btn btn-danger float-right"><i class="fa fa-trash-alt"></i> {{ trans('AMER::actions.delete') }}</button>
            @endif
            @if(!empty($saveAction['options']))
            </div>
            @endif
        </div>
    </div>
</form>
</div>
@push('after_scripts')
@loadScriptOnce('js/Amer/form-operation.js')
@if ($Amer->get('update.showDeleteButton') && $Amer->get('delete.configuration') && $Amer->hasAccess('delete'))
<script>
    function confirmAndDeleteEntry() {
        //<!-- footer.blade -->
        // Ask for confirmation before deleting an item
        swal({
            title: "{!! trans('AMER::actions.warning') !!}",
            text: "{!! trans('AMER::actions.delete_confirm') !!}",
            icon: "warning",
            buttons: ["{!! trans('AMER::actions.cancel') !!}", "{!! trans('AMER::actions.delete') !!}"],
            dangerMode: true,
        }).then((value) => {
            if (value) {
                $.ajax({
                    url: '{{ url($Amer->route.'/'.$entry->getKey()) }}',
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
                                    title: "{!! trans('AMER::actions.delete_confirmation_not_title') !!}",
                                    text: "{!! trans('AMER::actions.delete_confirmation_not_message') !!}",
                                    icon: "error",
                                    timer: 4000,
                                    buttons: false,
                                });
                            }
                        }
                        // All is good, show a success message!
                        swal({
                            title: "{!! trans('AMER::actions.delete_confirmation_title') !!}",
                            text: "{!! trans('AMER::actions.delete_confirmation_message') !!}",
                            icon: "success",
                            buttons: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                        });

                        // Redirect in 1 sec so that admins get to see the success message
                        setTimeout(function () {
                            window.location.href = '{{ is_bool($Amer->get('update.showDeleteButton')) ? url($Amer->route) : (string) $Amer->get('update.showDeleteButton') }}';
                        }, 1000);
                    },
                    error: function() {
                        // Show an alert with the result
                        swal({
                            title: "{!! trans('AMER::actions.delete_confirmation_not_title') !!}",
                            text: "{!! trans('AMER::actions.delete_confirmation_not_message') !!}",
                            icon: "error",
                            timer: 4000,
                            buttons: false,
                        });
                    }
                });
            }
        });
    }
</script>
@endif

@endpush